<?php

namespace App\Http\Controllers;

use App\Models\Borrower;
use App\Models\Resource;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Authorization
        if (! in_array(Auth::user()->role, ['student', 'faculty'])) {
            abort(403);
        }

        $filter = $request->get('filter', 'latest');

        // Base query with eager loading (include 'user' for uploader, ensure 'views' column is selected)
        $featuredQuery = Resource::featured()
            ->with(['authors', 'tags', 'ratings', 'user']) // Add 'user' for role/full_name
            ->select(['*', 'views']); // Explicitly select views column

        // Apply filter - limit to 7 for homepage
        switch ($filter) {
            case 'popular_month':
                $featuredResources = $featuredQuery->popularThisMonth()->take(7)->get();
                break;
            case 'popular_year':
                $featuredResources = $featuredQuery->popularThisYear()->take(7)->get();
                break;
            case 'latest':
            default:
                $featuredResources = $featuredQuery->latestUploads()->take(7)->get();
                break;
        }

        // Append accessors (remove 'views'—it's a raw column)
        $featuredResources = $featuredResources->append([
            'average_rating',
            'formatted_publish_date',
            'authors',
            'tags',
        ]);

        // Get user's borrow list to check if resources are already borrowed (exclude rejected)
        $userBorrowIds = Borrower::where('UID', Auth::id())
            ->where('isReturned', 0)
            ->whereNull('rejection_reason')
            ->pluck('resource_id')
            ->toArray();

        // Get rejected requests for each resource (only unapproved ones with rejection_reason)
        $rejectedRequests = Borrower::where('UID', Auth::id())
            ->whereNotNull('rejection_reason')
            ->whereNull('Approved_Date')
            ->get()
            ->groupBy('resource_id')
            ->map(function ($borrows) {
                return $borrows->sortByDesc('created_at')->first();
            });

        // Add is_borrowed and rejection flags to each resource
        $featuredResources->each(function ($resource) use ($userBorrowIds, $rejectedRequests) {
            $resource->is_borrowed = in_array($resource->Resource_ID, $userBorrowIds);
            $rejectedBorrow = $rejectedRequests->get($resource->Resource_ID);
            $resource->is_rejected = $rejectedBorrow !== null;
            $resource->rejection_reason = $rejectedBorrow?->rejection_reason;
        });

        // Community Uploads (also select views, remove from append)
        $communityUploads = Resource::communityUploads()
            ->where('approval_status', 'approved') // Only show approved community uploads
            ->with(['authors', 'tags', 'user', 'owner.campus', 'owner.student.course'])
            ->select(['*', 'views'])
            ->latestUploads()
            ->take(10)
            ->get()
            ->append(['authors', 'tags']); // Remove 'views' from here too

        // Get popular tags (tags used by most resources)
        $popularTags = Tag::select('tags.*', DB::raw('COUNT(resource_tags.resource_id) as resource_count'))
            ->join('resource_tags', 'tags.id', '=', 'resource_tags.tag_id')
            ->groupBy('tags.id', 'tags.name', 'tags.created_at', 'tags.updated_at')
            ->orderBy('resource_count', 'desc')
            ->take(10)
            ->get();

        // Format data for Inertia - accessors are already appended
        $formattedFeaturedResources = $featuredResources->map(function ($resource) {
            // Get tags from the relationship directly (not the accessor)
            $tagsRelation = $resource->relationLoaded('tags') ? $resource->getRelation('tags') : null;
            $formattedTags = [];

            if ($tagsRelation && $tagsRelation->isNotEmpty()) {
                $formattedTags = $tagsRelation->pluck('name')->filter()->values()->toArray();
            }

            return [
                'Resource_ID' => $resource->Resource_ID,
                'Resource_Name' => $resource->Resource_Name,
                'thumbnail_path' => $resource->thumbnail_path,
                'average_rating' => (string) ($resource->average_rating ?? '0.0'),
                'formatted_publish_date' => $resource->formatted_publish_date ?? 'N/A',
                'authors' => $resource->authors ?? 'Unknown Author',
                'tags' => $formattedTags,
                'is_borrowed' => $resource->is_borrowed ?? false,
                'is_rejected' => $resource->is_rejected ?? false,
                'rejection_reason' => $resource->rejection_reason ?? null,
                'Description' => $resource->Description ?? null,
                'views' => (int) ($resource->views ?? 0),
            ];
        });

        $formattedCommunityUploads = $communityUploads->map(function ($resource) {
            return [
                'Resource_ID' => $resource->Resource_ID,
                'Resource_Name' => $resource->Resource_Name,
                'user' => $resource->user ? [
                    'full_name' => $resource->user->full_name ?? 'Unknown',
                ] : ['full_name' => 'Unknown'],
            ];
        });

        return Inertia::render('Home', [
            'featuredResources' => $formattedFeaturedResources,
            'communityUploads' => $formattedCommunityUploads,
            'filter' => $filter,
            'popularTags' => $popularTags->map(fn ($tag) => [
                'id' => $tag->id,
                'name' => $tag->name,
            ]),
        ]);
    }

    public function featured(Request $request)
    {
        // Authorization - allow all authenticated users
        $user = Auth::user();
        if (! in_array($user->role, ['student', 'faculty', 'librarian'])) {
            abort(403);
        }

        $filter = $request->get('filter', 'latest');

        // Base query with eager loading
        $featuredQuery = Resource::featured()
            ->with(['authors', 'tags', 'ratings', 'user'])
            ->select(['*', 'views']);

        // Apply filter - show ALL resources (no limit)
        switch ($filter) {
            case 'popular_month':
                $featuredResources = $featuredQuery->popularThisMonth()->get();
                break;
            case 'popular_year':
                $featuredResources = $featuredQuery->popularThisYear()->get();
                break;
            case 'latest':
            default:
                $featuredResources = $featuredQuery->latestUploads()->get();
                break;
        }

        // Append accessors
        $featuredResources = $featuredResources->append([
            'average_rating',
            'formatted_publish_date',
            'authors',
            'tags',
        ]);

        // Get user's borrow list (exclude returned and rejected)
        $userBorrowIds = Borrower::where('UID', Auth::id())
            ->where('isReturned', 0)
            ->whereNull('rejection_reason')
            ->pluck('resource_id')
            ->toArray();

        // Add is_borrowed flag
        $featuredResources->each(function ($resource) use ($userBorrowIds) {
            $resource->is_borrowed = in_array($resource->Resource_ID, $userBorrowIds);
        });

        // Format data for Inertia
        $formattedFeaturedResources = $featuredResources->map(function ($resource) {
            // Get tags from the relationship directly (not the accessor)
            $tagsRelation = $resource->relationLoaded('tags') ? $resource->getRelation('tags') : null;
            $formattedTags = [];

            if ($tagsRelation && $tagsRelation->isNotEmpty()) {
                $formattedTags = $tagsRelation->pluck('name')->filter()->values()->toArray();
            }

            return [
                'Resource_ID' => $resource->Resource_ID,
                'Resource_Name' => $resource->Resource_Name,
                'thumbnail_path' => $resource->thumbnail_path,
                'average_rating' => (string) ($resource->average_rating ?? '0.0'),
                'formatted_publish_date' => $resource->formatted_publish_date ?? 'N/A',
                'authors' => $resource->authors ?? 'Unknown Author',
                'tags' => $formattedTags,
                'is_borrowed' => $resource->is_borrowed ?? false,
                'Description' => $resource->Description ?? null,
                'views' => (int) ($resource->views ?? 0),
            ];
        });

        return Inertia::render('Featured', [
            'featuredResources' => $formattedFeaturedResources,
            'filter' => $filter,
        ]);
    }

    public function show($id)
    {
        // Allow all authenticated users (student, faculty, librarian, admin)
        if (! Auth::check()) {
            abort(403);
        }

        $resource = Resource::with(['user', 'authors', 'tags', 'ratings'])->findOrFail($id);

        // Track view
        $resource->incrementViews();

        // Get user's borrow list (exclude rejected requests - allow re-request)
        $userBorrowIds = Borrower::where('UID', Auth::id())
            ->where('resource_id', $resource->Resource_ID)
            ->where('isReturned', 0)
            ->whereNull('rejection_reason') // Exclude rejected requests
            ->exists();

        // Only get rejected borrows that haven't been approved (rejection_reason should be null after approval)
        $rejectedBorrow = Borrower::where('UID', Auth::id())
            ->where('resource_id', $resource->Resource_ID)
            ->whereNotNull('rejection_reason')
            ->whereNull('Approved_Date') // Only unapproved rejected requests
            ->latest()
            ->first();

        $resource->is_borrowed = $userBorrowIds;
        $resource->is_rejected = $rejectedBorrow !== null;
        $resource->rejection_reason = $rejectedBorrow?->rejection_reason;

        // Append accessors
        $resource->append([
            'average_rating',
            'formatted_publish_date',
            'authors',
            'tags',
        ]);

        // Format tags
        $tagsRelation = $resource->relationLoaded('tags') ? $resource->getRelation('tags') : null;
        $formattedTags = [];
        if ($tagsRelation && $tagsRelation->isNotEmpty()) {
            $formattedTags = $tagsRelation->pluck('name')->filter()->values()->toArray();
        }

        return Inertia::render('ResourceView', [
            'resource' => [
                'Resource_ID' => $resource->Resource_ID,
                'Resource_Name' => $resource->Resource_Name,
                'thumbnail_path' => $resource->thumbnail_path,
                'average_rating' => (string) ($resource->average_rating ?? '0.0'),
                'formatted_publish_date' => $resource->formatted_publish_date ?? 'N/A',
                'authors' => $resource->authors ?? 'Unknown Author',
                'tags' => $formattedTags,
                'is_borrowed' => $resource->is_borrowed ?? false,
                'is_rejected' => $resource->is_rejected ?? false,
                'rejection_reason' => $resource->rejection_reason ?? null,
                'Description' => $resource->Description ?? null,
                'views' => (int) ($resource->views ?? 0),
                'owner_id' => $resource->owner_id ?? null,
                'Type' => $resource->Type ?? null,
            ],
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $resources = Resource::featured()
            ->with(['authors', 'tags', 'ratings'])
            ->where(function ($q) use ($query) {
                $q->where('Resource_Name', 'like', "%{$query}%")
                    ->orWhereHas('authors', function ($subQ) use ($query) {
                        $subQ->where('name', 'like', "%{$query}%");
                    })
                    ->orWhereHas('tags', function ($subQ) use ($query) {
                        $subQ->where('name', 'like', "%{$query}%");
                    });
            })
            ->take(10)
            ->get()
            ->append(['average_rating', 'formatted_publish_date', 'authors', 'tags']);

        // Get user's borrow list
        $userBorrowIds = [];
        if (Auth::check()) {
            $userBorrowIds = Borrower::where('UID', Auth::id())
                ->where('isReturned', 0)
                ->whereNull('rejection_reason')
                ->pluck('resource_id')
                ->toArray();
        }

        $formattedResources = $resources->map(function ($resource) use ($userBorrowIds) {
            $tagsRelation = $resource->relationLoaded('tags') ? $resource->getRelation('tags') : null;
            $formattedTags = [];

            if ($tagsRelation && $tagsRelation->isNotEmpty()) {
                $formattedTags = $tagsRelation->pluck('name')->filter()->values()->toArray();
            }

            return [
                'Resource_ID' => $resource->Resource_ID,
                'Resource_Name' => $resource->Resource_Name,
                'thumbnail_path' => $resource->thumbnail_path,
                'average_rating' => (string) ($resource->average_rating ?? '0.0'),
                'formatted_publish_date' => $resource->formatted_publish_date ?? 'N/A',
                'authors' => $resource->authors ?? 'Unknown Author',
                'tags' => $formattedTags,
                'is_borrowed' => in_array($resource->Resource_ID, $userBorrowIds),
                'Description' => $resource->Description ?? null,
                'views' => (int) ($resource->views ?? 0),
            ];
        });

        return response()->json($formattedResources);
    }

    public function communityUploads()
    {
        // Get approved community uploads
        $communityUploads = Resource::where('Type', 'Community Uploads')
            ->where('approval_status', 'approved')
            ->with(['authors', 'tags', 'ratings', 'user', 'owner.campus', 'owner.student.course'])
            ->latest()
            ->get()
            ->append([
                'average_rating',
                'formatted_publish_date',
                'authors',
                'tags',
            ]);

        $formattedUploads = $communityUploads->map(function ($resource) {
            $tagsRelation = $resource->relationLoaded('tags') ? $resource->getRelation('tags') : null;
            $formattedTags = [];
            if ($tagsRelation && $tagsRelation->isNotEmpty()) {
                $formattedTags = $tagsRelation->pluck('name')->filter()->values()->toArray();
            }

            $owner = $resource->owner;
            $uploaderInfo = null;
            if ($owner) {
                $uploaderInfo = [
                    'name' => $owner->full_name ?? 'Unknown',
                    'profile_picture' => $owner->profile_picture ?? null,
                    'campus' => $owner->campus->Campus_Name ?? null,
                    'role' => $owner->role ?? null,
                ];

                // Add course info for students
                if ($owner->role === 'student' && $owner->student && $owner->student->course) {
                    $uploaderInfo['course'] = $owner->student->course->code ?? null;
                }
            }

            return [
                'Resource_ID' => $resource->Resource_ID,
                'Resource_Name' => $resource->Resource_Name,
                'thumbnail_path' => $resource->thumbnail_path,
                'authors' => $resource->authors ?? 'Unknown Author',
                'tags' => $formattedTags,
                'average_rating' => (string) ($resource->average_rating ?? '0.0'),
                'views' => (int) ($resource->views ?? 0),
                'uploader' => $uploaderInfo,
            ];
        });

        return Inertia::render('Librarian/CommunityUploads', [
            'communityUploads' => $formattedUploads,
        ]);
    }
}
