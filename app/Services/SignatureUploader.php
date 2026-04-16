<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class SignatureUploader
{
    public static function handle($input, string $prefix, string $path): ?string
    {
        // return $fullPath;
        if (!$input) {
            return null;
        }

        // 1️⃣ Jika dari signature pad (base64)
        if (is_string($input) && str_starts_with($input, 'data:image')) {

            $base64 = preg_replace('/^data:image\/(png|jpeg|jpg);base64,/', '', $input);
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

        // 2️⃣ Jika dari upload file
        if ($input instanceof TemporaryUploadedFile) {

            $fileName = $prefix . Str::random(10) . '.' . $input->getClientOriginalExtension();

            return $input->storeAs($path, $fileName, 'public');
        }

        // 3️⃣ Jika sudah berupa path (kadang Filament kirim string)
        if (is_string($input) && Storage::disk('public')->exists($input)) {
            return $input;
        }

        return null;
    }
}
