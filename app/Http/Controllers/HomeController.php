<?php

namespace App\Http\Controllers;

use App\Models\Borrower;
use App\Models\Resource;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
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

        // Get user's borrow list to check if resources are already borrowed
        $userBorrowIds = Borrower::where('UID', Auth::id())
            ->where('isReturned', 0)
            ->pluck('resource_id')
            ->toArray();

        // Add is_borrowed flag to each resource
        $featuredResources->each(function ($resource) use ($userBorrowIds) {
            $resource->is_borrowed = in_array($resource->Resource_ID, $userBorrowIds);
        });

        // Community Uploads (also select views, remove from append)
        $communityUploads = Resource::communityUploads()
            ->with(['authors', 'tags', 'user'])
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

        // Get user's borrow list
        $userBorrowIds = Borrower::where('UID', Auth::id())
            ->where('isReturned', 0)
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
        if (! in_array(Auth::user()->role, ['student', 'faculty'])) {
            abort(403);
        }

        $resource = Resource::with(['user'])->findOrFail($id); // Load user if needed

        // Track view (now uses model method—see below)
        $resource->incrementViews();

        Log::info("View attempt for Resource ID {$id}: Success");

        // Redirect to file
        if ($resource->File_Path && Storage::disk('public')->exists($resource->File_Path)) {
            return redirect('/storage/'.$resource->File_Path);
        } else {
            Log::warning("File not found for Resource ID {$id}");

            return redirect()->back()->with('error', 'File not available.');
        }
    }
}
