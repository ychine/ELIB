<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Resource;
use Illuminate\Http\UploadedFile;
use App\Http\Controllers\ResourceController;

class GenerateThumbnails extends Command
{
    protected $signature = 'thumbnails:generate';
    protected $description = 'Generate thumbnails for existing PDF resources';

    public function handle()
    {
        $controller = app(ResourceController::class);
        $processed = 0;
        $succeeded = 0;
        $failed = 0;

        $this->info('Starting thumbnail generation...');

        Resource::whereNotNull('File_Path')
            ->where('File_Path', 'like', '%.pdf')
            ->chunk(10, function ($resources) use ($controller, &$processed, &$succeeded, &$failed) {
                foreach ($resources as $resource) {
                    $fullPath = storage_path('app/public/' . $resource->File_Path);
                    
                    if (file_exists($fullPath)) {
                        $this->line("Processing: {$resource->Resource_Name}");
                        
                        try {
                            $fakeFile = new UploadedFile(
                                $fullPath, 
                                basename($fullPath), 
                                'application/pdf', 
                                null, 
                                true
                            );
                            
                            if ($controller->generateThumbnail($resource, $fakeFile)) {
                                $this->info("  ✓ SUCCESS");
                                $succeeded++;
                            } else {
                                $this->warn("  ✗ FAILED");
                                $failed++;
                            }
                        } catch (\Exception $e) {
                            $this->error("  ✗ ERROR: " . $e->getMessage());
                            $failed++;
                        }
                        
                        $processed++;
                    } else {
                        $this->warn("File not found: {$resource->Resource_Name}");
                        $failed++;
                    }
                }
            });

        $this->newLine();
        $this->info("🎉 COMPLETE!");
        $this->table(
            ['Status', 'Count'],
            [
                ['Processed', $processed],
                ['Succeeded', $succeeded],
                ['Failed', $failed],
            ]
        );
        $this->info("Thumbnails saved to: storage/app/public/thumbnails/");

        return Command::SUCCESS;
    }
}