<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class SignatureUploader
{
    private const ALLOWED_EXTENSIONS = ['png', 'jpg', 'jpeg'];

    public static function handle($input, string $prefix, string $path): ?string
    {
        if (! $input) {
            return null;
        }

        if (is_string($input) && preg_match('/^data:image\/(png|jpeg|jpg);base64,/', $input, $matches)) {
            $ext = $matches[1] === 'jpeg' ? 'jpg' : $matches[1];
            $base64 = substr($input, strpos($input, ',') + 1);
            $imageData = base64_decode(str_replace(' ', '+', $base64), true);
            if ($imageData === false) {
                return null;
            }
            $fullPath = "{$path}/{$prefix}" . Str::random(10) . ".{$ext}";
            Storage::disk('public')->put($fullPath, $imageData);
            return $fullPath;
        }

        if ($input instanceof TemporaryUploadedFile) {
            $ext = strtolower($input->extension() ?: $input->getClientOriginalExtension());
            if (! in_array($ext, self::ALLOWED_EXTENSIONS, true)) {
                return null;
            }
            $fileName = $prefix . Str::random(10) . '.' . $ext;
            return $input->storeAs($path, $fileName, 'public');
        }

        if (is_string($input) && Storage::disk('public')->exists($input)) {
            return $input;
        }

        return null;
    }
}
