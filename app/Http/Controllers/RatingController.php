<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'resource_id' => 'required|exists:resources,Resource_ID',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        Rating::updateOrCreate(
            [
                'resource_id' => $validated['resource_id'],
                'user_id' => Auth::id(),
            ],
            [
                'rating' => $validated['rating'],
            ]
        );

        return back()->with('success', 'Rating submitted successfully!');
    }
}
