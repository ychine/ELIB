<?php

namespace App\Http\Controllers;

use App\Models\Borrower;
use App\Models\User;
use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class BorrowController extends Controller
{
    // Show all pending borrow requests (for Librarian)
    public function index()
    {
        $borrowRequests = Borrower::with(['user', 'resource'])
            ->where('isReturned', 0)
            ->latest()
            ->get();

        return view('borrowers', compact('borrowRequests'));
    }

    // Store a new borrow request (from user side)
    public function store(Request $request)
    {
        $request->validate([
            'resource_id' => 'required|exists:resources,Resource_ID'
        ]);

        $exists = Borrower::where('UID', Auth::id())
            ->where('resource_id', $request->resource_id)
            ->where('isReturned', 0)
            ->exists();

        if ($exists) {
            return back()->with('info', 'You already have this in your borrow list!');
        }

        Borrower::create([
            'UID' => Auth::id(),
            'resource_id' => $request->resource_id,
            'isReturned' => 0,
        ]);

        return back()->with('success', 'Added to your borrow list!');
    }

    // Approve borrow request
    public function approve($id)
    {
        $borrow = Borrower::findOrFail($id);
        $borrow->update(['isReturned' => 1]); // or use a status field like 'approved' => 1
        return back()->with('success', 'Borrow request approved.');
    }

    // Reject borrow request
    public function reject($id)
    {
        $borrow = Borrower::findOrFail($id);
        $borrow->delete(); // or mark as rejected
        return back()->with('success', 'Borrow request rejected.');
    }

    // Get borrower + resource details via AJAX
    public function details($id)
    {
        $borrow = Borrower::with(['user.campus', 'resource'])
                        ->findOrFail($id);

        $user = $borrow->user;

        return response()->json([
            'user' => [
                'full_name' => $user->full_name ?? 'N/A',
                'email'     => $user->email,
                'role'      => ucfirst($user->role),
                'verified'  => $user->email_verified_at ? 'Verified' : 'Not Verified',
            ],
            'campus' => $user->campus?->Campus_Name ?? 'N/A',
            'request' => [
                'created_at' => Carbon::parse($borrow->created_at)
                                    ->format('M d, Y h:i A')
            ],
            'resource' => [
                'Resource_Name' => $borrow->resource->Resource_Name
            ]
        ]);
    }
}