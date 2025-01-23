<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class ImageHelper
{
    public static function processImage($imageFile, $name, $path, $resizeDimensions = null, $isThumbnail = false)
    {
        // Generate image name
        $suffix = $isThumbnail ? 'thumbnail-' : '';
        $imageName = $suffix . time() . '-' . Str::of($name)->slug('-') . '.' . $imageFile->getClientOriginalExtension();

        // Define the destination path
        $destinationPath = public_path($path);
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }

        // Read and process the image
        $image = Image::read($imageFile);

        // Resize if dimensions are provided
        if ($resizeDimensions) {
            [$width, $height] = $resizeDimensions;
            $image->resize($width, $height);
        }

        // Save the image
        $image->save($destinationPath . $imageName);

        return $imageName;
    }

    public static function deleteImage($path, $imageName)
    {
        $imagePath = public_path($path . $imageName);
        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }
    }

    public static function replaceImage($imageFile, $name, $path, $oldImageName, $resizeDimensions = null, $isThumbnail = false)
    {
        // Delete the old image
        if ($oldImageName) {
            self::deleteImage($path, $oldImageName);
        }

        // Process and save the new image
        return self::processImage($imageFile, $name, $path, $resizeDimensions, $isThumbnail);
    }
}
