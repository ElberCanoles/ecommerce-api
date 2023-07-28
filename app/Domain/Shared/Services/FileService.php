<?php

declare(strict_types=1);

namespace App\Domain\Shared\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Exception;

class FileService
{
    private readonly string $diskName;

    public function __construct()
    {
        $this->diskName = config(key: 'filesystems.default');
    }

    public function uploadSingleFile(UploadedFile|string $file, string $relativePath): ?string
    {
        try {
            Storage::disk(name: $this->diskName)->put($relativePath, $file);

            return $this->makeUrlFromRelativePath($file->hashName(path: $relativePath));
        } catch (Exception $exception) {
            logger()->error(message: 'error uploading single file on FileService.uploadSingleFile', context: [
                'message' => $exception->getMessage()
            ]);

            return null;
        }
    }

    public function uploadMultipleFiles(array $files, string $relativePath): array
    {
        $response = [];

        foreach ($files as $file) {
            if (is_a(object_or_class: $file, class: UploadedFile::class)) {
                if ($fileUrl = $this->uploadSingleFile(file: $file, relativePath: $relativePath)) {
                    $response[] = $fileUrl;
                }
            }
        }

        return $response;
    }

    public function removeSingleFile(string $fullPath, $fromThePrefix): void
    {
        $relativePath = $this->makeRelativePathFromUrl(fileNameWithUrl: $fullPath, fromThePrefix: $fromThePrefix);

        if (Storage::disk(name: $this->diskName)->exists($relativePath)) {
            Storage::disk(name: $this->diskName)->delete($relativePath);
        }
    }

    public function removeMultipleFiles(array $filesUrl, $fromThePrefix): void
    {
        foreach ($filesUrl as $fileUrl) {
            $this->removeSingleFile($fileUrl, $fromThePrefix);
        }
    }

    public function makeUrlFromRelativePath(string $fileNameWithRelativePath): string
    {
        return Storage::url(path: $fileNameWithRelativePath);
    }

    public function makeRelativePathFromUrl(string $fileNameWithUrl, string $fromThePrefix): string
    {
        $position = strpos($fileNameWithUrl, $fromThePrefix);

        if ($position !== false) {
            return substr($fileNameWithUrl, $position);
        } else {
            return $fileNameWithUrl;
        }
    }
}
