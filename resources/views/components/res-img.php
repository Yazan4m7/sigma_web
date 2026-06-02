<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    /**
     * Define the widths for our responsive images.
     */
    protected $sizes = [
        's'   => 300,
        'm'   => 600,
        'l'   => 1200,
        'xl'  => 2000,
    ];

    public function store(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:10240',
        ]);

        if (!$request->hasFile('photo')) {
            return back()->with('error', 'No photo uploaded.');
        }

        $file = $request->file('photo');

        // 1. Generate a unique base filename
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = strtolower($file->getClientOriginalExtension());
        $baseName = time() . '-' . Str::slug($originalName); // e.g., "167888-my-image"
        $fullBaseName = "{$baseName}.{$extension}";

        // 2. Load the original image into memory using the correct GD function
        $sourceImage = null;
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                $sourceImage = @imagecreatefromjpeg($file->getRealPath());
                break;
            case 'png':
                $sourceImage = @imagecreatefrompng($file->getRealPath());
                break;
        }

        if (!$sourceImage) {
            return back()->with('error', 'Could not process this image type.');
        }

        $originalWidth = imagesx($sourceImage);
        $originalHeight = imagesy($sourceImage);

        // 3. Loop through sizes and create resized versions
        foreach ($this->sizes as $suffix => $width) {
            // Don't scale up smaller images
            if ($originalWidth < $width) {
                continue;
            }

            // Calculate new height
            $height = (int) (($originalHeight / $originalWidth) * $width);

            // Create a new blank image canvas
            $newImage = imagecreatetruecolor($width, $height);

            // Handle PNG transparency
            if ($extension == 'png') {
                imagealphablending($newImage, false);
                imagesavealpha($newImage, true);
                $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
                imagefilledrectangle($newImage, 0, 0, $width, $height, $transparent);
            }

            // Resize and copy the original image to the new canvas
            imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $width, $height, $originalWidth, $originalHeight);

            // 4. Save the new image to a temporary file
            $tempPath = tempnam(sys_get_temp_dir(), 'resized');
            switch ($extension) {
                case 'jpg':
                case 'jpeg':
                    imagejpeg($newImage, $tempPath, 80); // 80% quality
                    break;
                case 'png':
                    imagepng($newImage, $tempPath, 6); // 6/9 compression
                    break;
            }

            // 5. Move from temp file to Laravel Storage
            $newFilename = "{$baseName}-{$suffix}.{$extension}";
            Storage::putFileAs('public/photos', $tempPath, $newFilename);

            // 6. Clean up memory
            imagedestroy($newImage);
            unlink($tempPath);
        }

        // Clean up the original source image from memory
        imagedestroy($sourceImage);

        // 7. Save the base name to your database
        // $photo = new Photo();
        // $photo->filename = $fullBaseName;
        // $photo->alt_text = "Some alt text";
        // $photo->save();

        return back()->with('success', 'Photo uploaded and resized!');
    }
}
