<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resource;
use App\Models\Author;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;

class ResourceController extends Controller
{
    public function index()
    {
        $resources = Resource::with(['authors', 'user', 'tags'])
            ->latest()
            ->paginate(25);

        return view('resourceManagement', compact('resources'));
    }

    public function store(Request $request)
    {
        Log::info('UPLOAD STARTED', [
            'user_id' => Auth::id(),
            'file_size' => $request->file('file')?->getSize(),
        ]);

        $validated = $request->validate([
            'Resource_Name' => 'required|string|max:255',
            'authors' => 'required|string|max:500',
            'Description' => 'required|string',
            'publish_year' => 'nullable|integer|min:1900|max:2030',
            'publish_month' => 'nullable|integer|min:1|max:12',
            'publish_day' => 'nullable|integer|min:1|max:31',
            'file' => 'required|file|mimes:pdf,doc,docx,zip|max:102400',
            'tags' => 'nullable|string|max:500',
        ]);

        try {
            $uploadedFile = $request->file('file');
            $path = $uploadedFile->store('resources', 'public');

            $resource = Resource::create([
                'Resource_Name' => $validated['Resource_Name'],
                'File_Path'     => $path,
                'Type'          => 'Featured',
                'publish_year'  => $validated['publish_year'] ?? null,
                'publish_month' => $validated['publish_month'] ?? null,
                'publish_day'   => $validated['publish_day'] ?? null,
                'Uploaded_By'   => Auth::id(),
                'Description'   => $validated['Description'],
                'status'        => 'Available',
                'views'         => 0, // Explicitly set initial views to 0
            ]);

            // Attempt thumbnail generation separately so it doesn't fail the whole upload
            try {
                $this->generateThumbnail($resource, $uploadedFile);
                Log::info('Thumbnail generated successfully for Resource ID: ' . $resource->Resource_ID);
            } catch (\Exception $thumbnailException) {
                Log::warning('Thumbnail generation failed for Resource ID ' . $resource->Resource_ID . ': ' . $thumbnailException->getMessage());
            }

            // Sync authors
            $this->syncAuthors($resource, $validated['authors']);
            $this->syncTags($resource, $validated['tags'] ?? '');

            return redirect()->back()->with('success', 'Resource uploaded successfully!');

        } catch (\Exception $e) {
            Log::error('Upload failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Upload failed: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Resource $resource)
    {
        $validated = $request->validate([
            'Resource_Name' => 'required|string|max:255',
            'authors' => 'required|string|max:500',
            'Description' => 'required|string',
            'publish_year' => 'nullable|integer|min:1900|max:2030',
            'publish_month' => 'nullable|integer|min:1|max:12',
            'publish_day' => 'nullable|integer|min:1|max:31',
            'file' => 'nullable|file|mimes:pdf,doc,docx,zip|max:102400',
            'status' => 'required|in:Available,Unavailable',
            'tags' => 'nullable|string|max:500',
        ]);

        $updateData = [
            'Resource_Name' => $validated['Resource_Name'],
            'Description'   => $validated['Description'],
            'publish_year'  => $validated['publish_year'] ?? null,
            'publish_month' => $validated['publish_month'] ?? null,
            'publish_day'   => $validated['publish_day'] ?? null,
            'status'        => $validated['status'],
        ];

        if ($request->hasFile('file')) {
            // Delete old file
            if ($resource->File_Path && Storage::disk('public')->exists($resource->File_Path)) {
                Storage::disk('public')->delete($resource->File_Path);
            }

            $uploadedFile = $request->file('file');
            $updateData['File_Path'] = $uploadedFile->store('resources', 'public');

            // Regenerate thumbnail separately
            try {
                $this->generateThumbnail($resource, $uploadedFile);
                Log::info('Thumbnail regenerated successfully for Resource ID: ' . $resource->Resource_ID);
            } catch (\Exception $thumbnailException) {
                Log::warning('Thumbnail regeneration failed for Resource ID ' . $resource->Resource_ID . ': ' . $thumbnailException->getMessage());
            }
        }

        $resource->update($updateData);
        $this->syncAuthors($resource, $validated['authors']);
        $this->syncTags($resource, $validated['tags'] ?? '');

        return redirect()->back()->with('success', 'Resource updated successfully!');
    }

    public function destroy(Resource $resource)
    {
        // Delete main file
        if ($resource->File_Path && Storage::disk('public')->exists($resource->File_Path)) {
            Storage::disk('public')->delete($resource->File_Path);
        }

        // Delete thumbnail
        if ($resource->thumbnail_path && Storage::disk('public')->exists($resource->thumbnail_path)) {
            Storage::disk('public')->delete($resource->thumbnail_path);
        }

        $resource->authors()->detach();
        $resource->tags()->detach();
        $resource->delete();

        return redirect()->back()->with('success', 'Resource deleted successfully.');
    }

    // Method to increment views via AJAX (for modal opens)
    public function incrementView(Resource $resource)
    {
        // Prevent multiple views from same user in short time (1 hour)
        $sessionKey = "viewed_{$resource->Resource_ID}_" . Auth::id();
        if (!Session::has($sessionKey)) {
            $resource->increment('views');
            Session::put($sessionKey, true);
            Session::save(); // Persist immediately
            Log::info("AJAX View incremented for Resource ID {$resource->Resource_ID} by User ID " . Auth::id() . ". New total: " . $resource->fresh()->views);
        } else {
            Log::info("AJAX View skipped (duplicate) for Resource ID {$resource->Resource_ID} by User ID " . Auth::id());
        }

        return response()->json([
            'success' => true,
            'views' => $resource->fresh()->views // Fresh to get updated value
        ]);
    }

    // ──────────────────────────────────────────────
    // HELPER METHODS
    // ──────────────────────────────────────────────

    public function generateThumbnail(Resource $resource, $file)
    {
        // Only generate for PDFs
        if ($file->getMimeType() !== 'application/pdf') {
            $resource->thumbnail_path = null;
            $resource->save();
            return false;
        }

        try {
            $pdfPath = $file->getRealPath();
            $thumbnailDir = 'thumbnails';
            $thumbnailFilename = $resource->Resource_ID . '.jpg';
            $thumbnailPath = $thumbnailDir . '/' . $thumbnailFilename;
            $fullThumbnailPath = storage_path('app/public/' . $thumbnailPath);

            // Create directory if not exists
            Storage::disk('public')->makeDirectory($thumbnailDir);

            // Generate first page as JPG using Imagick directly
            $imagick = new \Imagick();
            $imagick->setResolution(150, 150);
            $imagick->readImage($pdfPath . '[0]'); // [0] = first page only
            $imagick->setImageFormat('jpg');
            $imagick->setImageCompressionQuality(85);
            
            // Resize to thumbnail size (optional)
            $imagick->thumbnailImage(800, 0); // 800px width, auto height
            
            $imagick->writeImage($fullThumbnailPath);
            $imagick->clear();
            $imagick->destroy();

            $resource->thumbnail_path = $thumbnailPath;
            $resource->save();

            return true;

        } catch (\Exception $e) {
            Log::warning("Thumbnail generation failed for Resource ID {$resource->Resource_ID}: " . $e->getMessage());
            $resource->thumbnail_path = null;
            $resource->save();
            return false;
        }
    }

    private function syncAuthors(Resource $resource, $authorString)
    {
        $resource->authors()->detach();
        $authorNames = array_filter(array_map('trim', explode(',', $authorString)));

        foreach ($authorNames as $name) {
            if ($name) {
                $author = Author::firstOrCreate(['name' => $name]);
                $resource->authors()->attach($author->id);
            }
        }
    }

    private function syncTags(Resource $resource, $tagString)
    {
        $resource->tags()->detach();
        $tagNames = array_filter(array_map('trim', explode(',', $tagString)));

        foreach ($tagNames as $name) {
            if ($name) {
                $tag = Tag::firstOrCreate(['name' => $name]);
                $resource->tags()->attach($tag->id);
            }
        }
    }
}