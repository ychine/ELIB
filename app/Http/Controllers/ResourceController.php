<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ResourceController extends Controller
{
    // ADD THIS METHOD — THIS IS WHAT WAS MISSING!!!
    public function index()
    {
        $resources = Resource::with('user')->get();
        return view('resourceManagement', compact('resources'));
    }

    public function store(Request $request)
    {
        ini_set('display_errors', 1);
        error_reporting(E_ALL);

        Log::info('UPLOAD STARTED', [
            'file_size' => $request->file('file')?->getSize() ?? 'NO FILE',
            'post_max' => ini_get('post_max_size'),
            'upload_max' => ini_get('upload_max_filesize')
        ]);

        if (!$request->hasFile('file')) {
            return redirect()->back()->with('error', 'NO FILE SELECTED!');
        }

        try {
            $path = $request->file('file')->store('resources', 'public');

            Resource::create([
                'Resource_Name' => $request->Resource_Name,
                'File_Path'     => $path,
                'Type'          => 'Featured',
                'Publish_Date'  => $request->publish_date,
                'Uploaded_By'   => Auth::id(),
                'Description'   => $request->Description,
                'status'        => 'Available',
                'author'        => $request->author,
            ]);

            return redirect()->back()->with('success', '47MB FILE UPLOADED SUCCESSFULLY!');

        } catch (\Exception $e) {
            Log::error('UPLOAD ERROR: ' . $e->getMessage());
            return redirect()->back()->with('error', 'ERROR: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Resource $resource)
    {
        $validated = $request->validate([
            'Resource_Name' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'Description' => 'required|string',
            'publish_date' => 'required|date',
            'file' => 'nullable|file|mimes:pdf,doc,docx,zip|max:102400',
            'status' => 'required|string',
        ]);

        if ($request->hasFile('file')) {
            // Delete old file if needed
            Storage::disk('public')->delete($resource->File_Path);
            $path = $request->file('file')->store('resources', 'public');
            $validated['File_Path'] = $path;
        }

        $resource->update($validated);

        return redirect()->back()->with('success', 'Resource updated successfully');
    }
}