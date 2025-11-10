<?php

namespace App\Http\Controllers;

use App\Models\Borrower;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BorrowController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'resource_id' => 'required|exists:resources,Resource_ID'
        ]);

        // Prevent duplicate borrow requests
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
}