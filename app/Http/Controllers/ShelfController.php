<?php

namespace App\Http\Controllers;

use App\Models\Borrower;
use App\Models\Rating;
use App\Models\Resource;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShelfController extends Controller
{
    public function index(Request $req)
    {
        $userId = auth()->id();

        $borrows = Borrower::with(['resource.authors', 'resource.tags', 'resource.ratings'])
            ->where('UID', $userId)
            ->latest()
            ->get();

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
                'Approved_Date' => $borrow->Approved_Date,
                'Return_Date' => $borrow->Return_Date,
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

        return Inertia::render('YourShelf', [
            'borrows' => $formattedBorrows,
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
}
