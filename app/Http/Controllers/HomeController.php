<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Authorization
        if (!in_array(Auth::user()->role, ['student', 'faculty'])) {
            abort(403);
        }

        $filter = $request->get('filter', 'latest');

        // Base query with eager loading
        $featuredQuery = Resource::featured()
            ->with(['authors', 'tags', 'ratings']);

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

        // Append accessors so they appear in toJson() for the modal
        $featuredResources = $featuredResources->append([
            'average_rating',
            'formatted_publish_date',
            'authors',
            'tags'
        ]);

        // Community Uploads
        $communityUploads = Resource::communityUploads()
            ->with(['authors', 'tags', 'user'])
            ->latestUploads()
            ->take(10)
            ->get()
            ->append(['authors', 'tags']); // Optional: for future modal use

        return view('homeUser', compact('featuredResources', 'communityUploads', 'filter'));
    }

    public function show($id)
    {
        if (!in_array(Auth::user()->role, ['student', 'faculty'])) {
            abort(403);
        }

        $resource = Resource::findOrFail($id);
        
        // Track view
        $resource->incrementViews(auth()->id());

        // Redirect to file
        return redirect('/storage/' . $resource->File_Path);
    }
}