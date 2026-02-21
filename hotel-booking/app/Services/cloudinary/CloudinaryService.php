<?php

namespace App\Services\cloudinary;

use Cloudinary\Api\Exception\ApiError;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\UploadedFile;
use Throwable;

class CloudinaryService
{
    public function upload(
        UploadedFile $file,
        string $folder,
        ?string $publicId = null
    ): array {
        $result = Cloudinary::uploadApi()->upload(
            $file->getRealPath(),
            [
                'folder' => $folder,
                'public_id' => $publicId,
                'resource_type' => 'image',
            ]
        );

        return [
            'url' => $result['secure_url'],
            'public_id' => $result['public_id'],
        ];
    }

    public function delete(string $publicId): void
    {
        try {
            $result = Cloudinary::uploadApi()->destroy($publicId);

            if (($result['result'] ?? null) !== 'ok') {
                logger()->warning('Cloudinary delete failed', [
                    'public_id' => $publicId,
                    'response' => $result,
                ]);
            }

        } catch (ApiError $e) {
            logger()->error('Cloudinary exception', [
                'public_id' => $publicId,
                'message' => $e->getMessage(),
            ]);
        } catch (Throwable $e) {
            logger()->error('Unexpected error when deleting image', [
                'public_id' => $publicId,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
