<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Models\Borrower;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Authorization
        if (!in_array(Auth::user()->role, ['student', 'faculty'])) {
            abort(403);
        }

        $filter = $request->get('filter', 'latest');

        // Base query with eager loading (include 'user' for uploader, ensure 'views' column is selected)
        $featuredQuery = Resource::featured()
            ->with(['authors', 'tags', 'ratings', 'user']) // Add 'user' for role/full_name
            ->select(['*', 'views']); // Explicitly select views column

        // Apply filter
        switch ($filter) {
            case 'popular_month':
                $featuredResources = $featuredQuery->popularThisMonth()->take(12)->get();
                break;
            case 'popular_year':
                $featuredResources = $featuredQuery->popularThisYear()->take(12)->get();
                break;
            case 'latest':
            default:
                $featuredResources = $featuredQuery->latestUploads()->take(12)->get();
                break;
        }

        // Append accessors (remove 'views'—it's a raw column)
        $featuredResources = $featuredResources->append([
            'average_rating',
            'formatted_publish_date',
            'authors',
            'tags'
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

        return view('homeUser', compact('featuredResources', 'communityUploads', 'filter'));
    }

    public function show($id)
    {
        if (!in_array(Auth::user()->role, ['student', 'faculty'])) {
            abort(403);
        }

        $resource = Resource::with(['user'])->findOrFail($id); // Load user if needed
        
        // Track view (now uses model method—see below)
        $incremented = $resource->incrementViews();
        
        Log::info("View attempt for Resource ID {$id}: " . ($incremented ? 'Success' : 'Skipped (duplicate)'));
        
        // Redirect to file
        if ($resource->File_Path && Storage::disk('public')->exists($resource->File_Path)) {
            return redirect('/storage/' . $resource->File_Path);
        } else {
            Log::warning("File not found for Resource ID {$id}");
            return redirect()->back()->with('error', 'File not available.');
        }
    }
}