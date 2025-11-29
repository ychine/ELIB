<?php

namespace App\Http\Controllers;

use App\Models\Borrower;
use App\Models\BorrowHistory;
use App\Models\Resource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class BorrowController extends Controller
{
    // Show all borrow requests (for Librarian)
    public function index()
    {
        $timezone = config('app.timezone');

        $formatIso = static function ($value) use ($timezone) {
            return $value ? $value->clone()->timezone($timezone)->toIso8601String() : null;
        };

        $latestBorrowerIds = Borrower::selectRaw('MAX(Borrower_ID) as latest_id')
            ->groupBy('UID', 'resource_id');

        $borrowRequests = Borrower::with(['user', 'resource'])
            ->whereIn('Borrower_ID', $latestBorrowerIds)
            ->latest()
            ->get()
            ->map(function ($borrow) use ($formatIso) {
                $isOverdue = false;
                if (! $borrow->isReturned && $borrow->Return_Date) {
                    $returnDate = Carbon::parse($borrow->Return_Date);
                    $isOverdue = $returnDate->isPast();
                }

                // Determine status
                $status = 'pending';
                if (! empty($borrow->rejection_reason) && empty($borrow->Approved_Date)) {
                    $status = 'rejected';
                } elseif ($borrow->isReturned) {
                    $status = 'returned';
                } elseif (! empty($borrow->Approved_Date)) {
                    $status = 'approved';
                }

                return [
                    'Borrower_ID' => $borrow->Borrower_ID,
                    'created_at' => $formatIso($borrow->created_at),
                    'Return_Date' => $formatIso($borrow->Return_Date),
                    'returned_at' => $formatIso($borrow->returned_at),
                    'Approved_Date' => $formatIso($borrow->Approved_Date),
                    'isReturned' => $borrow->isReturned,
                    'rejection_reason' => $borrow->rejection_reason,
                    'status' => $status,
                    'isOverdue' => $isOverdue,
                    'user' => [
                        'full_name' => $borrow->user->full_name ?? 'Unknown',
                        'email' => $borrow->user->email ?? 'N/A',
                    ],
                    'resource' => [
                        'Resource_Name' => $borrow->resource->Resource_Name ?? 'Unknown Resource',
                    ],
                ];
            });

        return Inertia::render('Librarian/Borrowers', [
            'borrowRequests' => $borrowRequests,
        ]);
    }

    // Store a new borrow request (from user side)
    public function store(Request $request)
    {
        $request->validate([
            'resource_id' => 'required|exists:resources,Resource_ID',
            'return_date' => 'required|date|after:now',
        ]);

        $activeBorrowCount = Borrower::where('UID', Auth::id())
            ->where('isReturned', 0)
            ->whereNull('rejection_reason')
            ->count();

        if ($activeBorrowCount >= 5) {
            throw ValidationException::withMessages([
                'limit' => 'You have reached the maximum of 5 active borrow requests. Please return a resource before requesting another.',
            ]);
        }

        // Check if there's an active (non-returned, non-rejected) request
        // This includes: pending requests, approved but not returned requests
        // Excludes: returned books (isReturned = 1), rejected requests (rejection_reason not null)
        $exists = Borrower::where('UID', Auth::id())
            ->where('resource_id', $request->resource_id)
            ->where('isReturned', 0) // Not returned yet
            ->whereNull('rejection_reason') // Not rejected
            ->exists();

        if ($exists) {
            return back()->with('info', 'You already have this in your borrow list!');
        }

        $latestBorrow = Borrower::where('UID', Auth::id())
            ->where('resource_id', $request->resource_id)
            ->latest('Borrower_ID')
            ->first();

        if ($latestBorrow && empty($latestBorrow->Approved_Date) && ! empty($latestBorrow->rejection_reason)) {
            // Reuse rejected request instead of creating another instance
            $latestBorrow->update([
                'Return_Date' => $request->return_date,
                'rejection_reason' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $borrow = $latestBorrow->fresh();
        } else {
            $borrow = Borrower::create([
                'UID' => Auth::id(),
                'resource_id' => $request->resource_id,
                'Return_Date' => $request->return_date,
                'isReturned' => 0,
            ]);
        }

        // Log history
        BorrowHistory::create([
            'borrower_id' => $borrow->Borrower_ID,
            'user_id' => Auth::id(),
            'resource_id' => $request->resource_id,
            'action' => 'requested',
            'return_date' => $request->return_date,
        ]);

        return back()->with('success', 'Added to your borrow list!');
    }

    // Cancel borrow request (user side)
    public function cancel($id)
    {
        $borrow = Borrower::where('Borrower_ID', $id)
            ->where('UID', Auth::id())
            ->whereNull('Approved_Date') // Only allow canceling pending requests
            ->firstOrFail();

        // Log history before deleting
        BorrowHistory::create([
            'borrower_id' => $borrow->Borrower_ID,
            'user_id' => $borrow->UID,
            'resource_id' => $borrow->resource_id,
            'action' => 'cancelled',
        ]);

        $borrow->delete();

        return back()->with('success', 'Borrow request cancelled successfully.');
    }

    // Approve borrow request
    public function approve($id)
    {
        $borrow = Borrower::with('resource')->findOrFail($id);

        // Check if this is a community upload - only owner can approve
        if ($borrow->resource && $borrow->resource->Type === 'Community Uploads') {
            if ($borrow->resource->owner_id !== Auth::id()) {
                return back()->with('error', 'Only the resource owner can approve borrow requests for community uploads.');
            }
        }

        $now = now();
        $borrow->update([
            'Approved_Date' => $now,
            'Approved_By' => Auth::id(),
            'isReturned' => 0, // Set to 0 when approved, 1 when returned
            'rejection_reason' => null, // Clear rejection reason when approved
        ]);

        // Log history
        BorrowHistory::create([
            'borrower_id' => $borrow->Borrower_ID,
            'user_id' => $borrow->UID,
            'resource_id' => $borrow->resource_id,
            'action_by' => Auth::id(),
            'action' => 'approved',
            'approved_at' => $now,
            'return_date' => $borrow->Return_Date,
        ]);

        return back()->with('success', 'Borrow request approved.');
    }

    // Reject borrow request
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $borrow = Borrower::with('resource')->findOrFail($id);

        // Check if this is a community upload - only owner can reject
        if ($borrow->resource && $borrow->resource->Type === 'Community Uploads') {
            if ($borrow->resource->owner_id !== Auth::id()) {
                return back()->with('error', 'Only the resource owner can reject borrow requests for community uploads.');
            }
        }

        // Keep the record but mark with rejection reason - student will see it in their shelf
        $borrow->update([
            'rejection_reason' => $request->rejection_reason,
        ]);
        // Don't delete - student needs to see the rejection reason

        // Log history
        BorrowHistory::create([
            'borrower_id' => $borrow->Borrower_ID,
            'user_id' => $borrow->UID,
            'resource_id' => $borrow->resource_id,
            'action_by' => Auth::id(),
            'action' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        return back()->with('success', 'Borrow request rejected.');
    }

    // Get borrower + resource details via AJAX
    public function details($id)
    {
        $borrow = Borrower::with(['user.campus', 'user.student.course', 'resource'])
            ->findOrFail($id);
        $timezone = config('app.timezone');
        $formatDisplay = static function ($value) use ($timezone) {
            return $value ? $value->clone()->timezone($timezone)->format('M d, Y h:i A') : null;
        };

        $user = $borrow->user;

        $returnDate = $borrow->Return_Date ? Carbon::parse($borrow->Return_Date) : null;
        $returnedAt = $borrow->returned_at ? Carbon::parse($borrow->returned_at) : null;

        $isOverdue = false;
        if (! $borrow->isReturned && $returnDate) {
            $isOverdue = $returnDate->isPast();
        }

        $wasReturnedLate = false;
        if ($returnedAt && $returnDate) {
            $wasReturnedLate = $returnedAt->greaterThan($returnDate);
        }

        $userData = [
            'full_name' => $user->full_name ?? 'N/A',
            'email' => $user->email,
            'role' => ucfirst($user->role),
            'verified' => $user->email_verified_at ? 'Verified' : 'Not Verified',
            'profile_picture' => $user->profile_picture ?? null,
        ];

        // Add student_number for students
        if ($user->role === 'student' && $user->student) {
            $userData['student_number'] = $user->student->student_number ?? 'N/A';
        }

        return response()->json([
            'user' => $userData,
            'campus' => $user->campus?->Campus_Name ?? 'N/A',
            'request' => [
                'created_at' => $formatDisplay($borrow->created_at),
                'Return_Date' => $formatDisplay($borrow->Return_Date),
                'returned_at' => $formatDisplay($borrow->returned_at),
                'isOverdue' => $isOverdue,
                'returned_late' => $wasReturnedLate,
            ],
            'resource' => [
                'Resource_Name' => $borrow->resource->Resource_Name,
            ],
        ]);
    }
}
