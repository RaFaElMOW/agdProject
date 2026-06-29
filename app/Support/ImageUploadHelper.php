<?php

namespace App\Support;

use App\Services\UploadService;

/**
 * Shared "keep existing value unless a new file was sent" pattern used by every
 * admin CRUD module that has an optional image field (team photo, testimonial photo,
 * project banner, sponsorship/book/media images, ...).
 */
class ImageUploadHelper
{
    public static function handle(string $fieldName, ?string $existingValue, string $subfolder): ?string
    {
        if (empty($_FILES[$fieldName]['name'])) {
            return $existingValue;
        }

        $service = new UploadService(Paths::uploads($subfolder));
        $filename = $service->storeImage($_FILES[$fieldName]);
        return 'uploads/' . trim($subfolder, '/') . '/' . $filename;
    }
}
