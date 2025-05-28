<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SignatureUploader
{
    public static function handle(?string $base64, string $prefix, string $path): ?string
    {
        if (!$base64 || !str_starts_with($base64, 'data:image')) {
            return null;
        }

        $base64 = preg_replace('/^data:image\/(png|jpeg|jpg);base64,/', '', $base64);
        $base64 = str_replace(' ', '+', $base64);
        $imageData = base64_decode($base64, true);

        if ($imageData === false) {
            return null;
        }

        $fileName = $prefix . Str::random(10) . '.jpg';
        $fullPath = $path . '/' . $fileName;

        Storage::disk('public')->put($fullPath, $imageData);

        return $fullPath;
    }
}
