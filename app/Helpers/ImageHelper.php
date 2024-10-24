<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Str;

class ImageHelper
{
    /**
     * Process the image and return the path.
     * Process the image and return the path.
     *
     * @param UploadedFile|null $imageFile
     * @param string $directory
     * @return string|null
     */
    public static function processImage(?UploadedFile $imageFile, string $directory): ?string
    {
        if ($imageFile) {
            $originalName = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $filename = Str::slug($originalName) . '_' . time() . '.' . $imageFile->getClientOriginalExtension();
            return $imageFile->storeAs($directory, $filename, 'public');
        }

        return null;
    }
}
