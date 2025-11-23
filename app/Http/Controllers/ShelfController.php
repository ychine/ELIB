<?php

namespace App\Http\Controllers;

use App\Models\Borrower;
use App\Models\BorrowHistory;
use App\Models\Rating;
use App\Models\Resource;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShelfController extends Controller
{
    public function index(Request $req)
    {
        $userId = auth()->id();

        // Get all borrows
        $allBorrows = Borrower::with(['resource.authors', 'resource.tags', 'resource.ratings'])
            ->where('UID', $userId)
            ->latest()
            ->get();

        // Group by resource_id and keep only the most relevant borrow for each resource
        // Priority: 1) Active/approved borrows, 2) Returned books, 3) Pending borrows, 4) Rejected borrows (only if no other exists)
        $borrows = $allBorrows
            ->groupBy('resource_id')
            ->map(function ($borrowsForResource) {
                // Sort by created_at descending to get most recent first
                $sorted = $borrowsForResource->sortByDesc('created_at');

                // Priority 1: Find active/approved borrows (not returned, not rejected)
                $active = $sorted->first(function ($b) {
                    return ! empty($b->Approved_Date) &&
                           empty($b->rejection_reason) &&
                           ! $b->isReturned;
                });

                if ($active) {
                    return $active;
                }

                // Priority 2: Find pending borrows (not rejected, not approved yet)
                $pending = $sorted->first(function ($b) {
                    return empty($b->Approved_Date) && empty($b->rejection_reason);
                });

                if ($pending) {
                    return $pending;
                }

                // Priority 3: Find returned books
                $returned = $sorted->first(function ($b) {
                    return ! empty($b->Approved_Date) &&
                           empty($b->rejection_reason) &&
                           $b->isReturned;
                });

                if ($returned) {
                    return $returned;
                }

                // Priority 4: If all are rejected, return the most recent rejected one
                return $sorted->first();
            })
            ->values();

        // Get user's ratings for all resources
        $userRatings = Rating::where('user_id', $userId)
            ->pluck('rating', 'resource_id')
            ->toArray();

        // Append accessors to resources
        $borrows->each(function ($borrow) {
            if ($borrow->resource) {
                $borrow->resource->append([
                    'average_rating',
                    'formatted_publish_date',
                    'authors',
                    'tags',
                ]);
            }
        });

        // Format borrows for Inertia
        $formattedBorrows = $borrows->map(function ($borrow) {
            $resource = $borrow->resource;
            $tagsRelation = $resource && $resource->relationLoaded('tags') ? $resource->getRelation('tags') : null;
            $formattedTags = [];

            if ($tagsRelation && $tagsRelation->isNotEmpty()) {
                $formattedTags = $tagsRelation->pluck('name')->filter()->values()->toArray();
            }

            return [
                'id' => $borrow->id,
                'Borrower_ID' => $borrow->Borrower_ID,
                'isReturned' => $borrow->isReturned ?? false,
                'isRejected' => ! empty($borrow->rejection_reason) && empty($borrow->Approved_Date),
                'Approved_Date' => $borrow->Approved_Date,
                'Return_Date' => $borrow->Return_Date,
                'rejection_reason' => $borrow->rejection_reason,
                'created_at' => $borrow->created_at,
                'userRating' => $resource ? ($userRatings[$resource->Resource_ID] ?? null) : null,
                'resource' => $resource ? [
                    'Resource_ID' => $resource->Resource_ID,
                    'Resource_Name' => $resource->Resource_Name,
                    'thumbnail_path' => $resource->thumbnail_path,
                    'Description' => $resource->Description,
                    'authors' => $resource->authors ?? 'Unknown Author',
                    'tags' => $formattedTags,
                    'average_rating' => (string) ($resource->average_rating ?? '0.0'),
                    'formatted_publish_date' => $resource->formatted_publish_date ?? 'N/A',
                    'views' => (int) ($resource->views ?? 0),
                ] : null,
            ];
        });

        // Get resources owned by the user (community uploads)
        $ownedResources = Resource::where('owner_id', $userId)
            ->where('Type', 'Community Uploads')
            ->with(['authors', 'tags', 'ratings'])
            ->get();

        // Get analytics for owned resources
        $ownedResourcesData = $ownedResources->map(function ($resource) {
            $resource->append([
                'average_rating',
                'formatted_publish_date',
                'authors',
                'tags',
            ]);

            // Get borrow requests for this resource
            $borrowRequests = Borrower::with('user')
                ->where('resource_id', $resource->Resource_ID)
                ->whereNull('Approved_Date')
                ->whereNull('rejection_reason')
                ->latest()
                ->get()
                ->map(function ($borrow) {
                    return [
                        'Borrower_ID' => $borrow->Borrower_ID,
                        'user' => [
                            'id' => $borrow->user->id ?? null,
                            'full_name' => $borrow->user->full_name ?? 'Unknown',
                            'email' => $borrow->user->email ?? 'N/A',
                        ],
                        'Return_Date' => $borrow->Return_Date,
                        'created_at' => $borrow->created_at,
                    ];
                });

            // Count total borrows (approved)
            $totalBorrows = Borrower::where('resource_id', $resource->Resource_ID)
                ->whereNotNull('Approved_Date')
                ->count();

            $tagsRelation = $resource->relationLoaded('tags') ? $resource->getRelation('tags') : null;
            $formattedTags = [];
            if ($tagsRelation && $tagsRelation->isNotEmpty()) {
                $formattedTags = $tagsRelation->pluck('name')->filter()->values()->toArray();
            }

            return [
                'Resource_ID' => $resource->Resource_ID,
                'Resource_Name' => $resource->Resource_Name,
                'thumbnail_path' => $resource->thumbnail_path,
                'Description' => $resource->Description,
                'authors' => $resource->authors ?? 'Unknown Author',
                'tags' => $formattedTags,
                'average_rating' => (string) ($resource->average_rating ?? '0.0'),
                'formatted_publish_date' => $resource->formatted_publish_date ?? 'N/A',
                'views' => (int) ($resource->views ?? 0),
                'approval_status' => $resource->approval_status ?? 'pending',
                'total_borrows' => $totalBorrows,
                'borrow_requests' => $borrowRequests,
            ];
        });

        return Inertia::render('YourShelf', [
            'borrows' => $formattedBorrows,
            'ownedResources' => $ownedResourcesData,
        ]);
    }

    public function viewBook($id)
    {
        $resource = Resource::findOrFail($id);
        $userId = auth()->id();

        // Check if user has an approved borrow for this resource
        $hasApprovedBorrow = \App\Models\Borrower::where('UID', $userId)
            ->where('resource_id', $id)
            ->whereNotNull('Approved_Date')
            ->exists();

        if (! $hasApprovedBorrow) {
            abort(403, 'You do not have permission to view this resource. Please wait for librarian approval.');
        }

        $filePath = storage_path('app/public/'.$resource->File_Path);

        if (! file_exists($filePath)) {
            abort(404, 'PDF file not found.');
        }

        // response()->file() automatically sets Content-Disposition for inline viewing
        // Only set Content-Type to avoid duplicate headers
        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    public function returnBook($id)
    {
        $borrow = Borrower::where('Borrower_ID', $id)
            ->where('UID', auth()->id())
            ->firstOrFail();

        if ($borrow->isReturned) {
            return back()->with('error', 'This book has already been returned.');
        }

        $now = now();
        $borrow->update([
            'isReturned' => 1,
            'Return_Date' => $now,
        ]);

        // Log history
        BorrowHistory::create([
            'borrower_id' => $borrow->Borrower_ID,
            'user_id' => $borrow->UID,
            'resource_id' => $borrow->resource_id,
            'action' => 'returned',
            'returned_at' => $now,
        ]);

        return back()->with('success', 'Book returned successfully.');
    }
}
