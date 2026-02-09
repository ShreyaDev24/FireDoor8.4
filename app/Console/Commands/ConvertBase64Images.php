<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ConvertBase64Images extends Command
{
    protected $signature = 'pdf:convert-base64';
    protected $description = 'Convert base64 images to physical PNG files';

    public function handle()
    {
        $images = config('constants.base64Images');

        if (!$images || !is_array($images)) {
            $this->error('No base64 images found.');
            return;
        }

        foreach ($images as $key => $base64) {

            // Skip if already converted
            $path = public_path("pdf-images/{$key}.png");
            if (file_exists($path)) {
                $this->info("Skipped: {$key}");
                continue;
            }

            // Remove data:image/...;base64,
            $data = explode(',', $base64)[1] ?? null;
            if (!$data) {
                $this->error("Invalid base64: {$key}");
                continue;
            }

            $image = base64_decode($data);

            file_put_contents($path, $image);

            $this->info("Created: {$key}.png");
        }

        $this->info('✅ Conversion complete');
    }
}
