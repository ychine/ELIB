<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Borrower;
use App\Models\Resource;

class ShelfController extends Controller
{
    public function index(Request $req) {
        $userId = auth()->id();

        $borrows = Borrower::with('resource')
            ->where('UID', $userId)
            ->latest()
            ->get();

        return view('yourshelf', compact('borrows'));
    }

    public function viewBook($id) {
        $resource = Resource::findOrFail($id);
        $userId = auth()->id();

        // Check if user has an approved borrow for this resource
        $hasApprovedBorrow = \App\Models\Borrower::where('UID', $userId)
            ->where('resource_id', $id)
            ->whereNotNull('Approved_Date')
            ->exists();

        if (!$hasApprovedBorrow) {
            abort(403, 'You do not have permission to view this resource. Please wait for librarian approval.');
        }

        $filePath = storage_path("app/public/" . $resource->File_Path);

        if (!file_exists($filePath)) {
            abort(404, 'PDF file not found.');
        }

        return response()->file($filePath, [
            "Content-Type" => "application/pdf",
            "Content-Disposition" => "inline; filename='{$resource->Resource_Name}.pdf'"
        ]);
    }
}