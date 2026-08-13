<?php

namespace App\Core;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class Image
{
    /**
     * Process an uploaded image
     */
    public static function process(string $sourcePath, string $destinationDir, string $filename, int $quality = 80): string
    {
        $manager = new ImageManager(new Driver());
        $image = $manager->read($sourcePath);

        // Ensure destination directory exists
        if (!is_dir($destinationDir)) {
            mkdir($destinationDir, 0755, true);
        }

        // Always save as webp for optimization
        $destinationPath = $destinationDir . '/' . $filename . '.webp';
        
        $image->toWebp($quality)->save($destinationPath);

        return $destinationPath;
    }

    /**
     * Create a thumbnail
     */
    public static function thumbnail(string $sourcePath, string $destinationDir, string $filename, int $width = 150, int $height = 150): string
    {
        $manager = new ImageManager(new Driver());
        $image = $manager->read($sourcePath);

        if (!is_dir($destinationDir)) {
            mkdir($destinationDir, 0755, true);
        }

        $destinationPath = $destinationDir . '/' . $filename . '_thumb.webp';
        
        $image->cover($width, $height)->toWebp(70)->save($destinationPath);

        return $destinationPath;
    }
}
