<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Small helper for storing and replacing uploaded images on the public disk.
 * Files land in storage/app/public/<folder> and are served via the storage symlink.
 */
trait HandlesFileUpload
{
    /**
     * Store an uploaded file and return its relative path (e.g. "products/abc.jpg").
     */
    protected function storeFile(?UploadedFile $file, string $folder): ?string
    {
        if (! $file) {
            return null;
        }

        $name = Str::random(20).'.'.$file->getClientOriginalExtension();

        return $file->storeAs($folder, $name, 'public');
    }

    /**
     * Replace an existing file: delete the old path, store and return the new one.
     * When no new file is given, the old path is kept untouched.
     */
    protected function replaceFile(?UploadedFile $file, string $folder, ?string $oldPath): ?string
    {
        if (! $file) {
            return $oldPath;
        }

        $this->deleteFile($oldPath);

        return $this->storeFile($file, $folder);
    }

    /**
     * Delete a stored file if present.
     */
    protected function deleteFile(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
