<?php

namespace App\Console\Commands;

use App\Models\Image;
use Illuminate\Console\Command;
use Storage;

class DeleteOldImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-old-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete old images.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $oldImages = Image::whereNull('imageable_id')
            ->where('created_at', '<', now()->subHours(24))
            ->get();

        $this->info("Deleting " . count($oldImages) . " old images.");

        foreach ($oldImages as $image) {
            if (Storage::exists($image->path)) {
                Storage::delete($image->path);
            }
            $image->delete();
        }
        $this->info("Old images deleted successfully.");
    }
}
