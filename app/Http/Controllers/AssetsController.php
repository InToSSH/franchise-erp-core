<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AssetsController extends Controller
{
    /**
     * Get media from storage folder. Allows setting ACL for assets
     *
     * @param int $mediaId
     * @param string $filename
     * @return BinaryFileResponse
     */
    public function getImage(string $path)
    {
        if (Storage::disk('images')->exists($path)) {
            return response()->file(Storage::disk('images')->path($path));
        } else {
            return response()->file(public_path('/img/no-img.png'));
        }
    }

    /**
     * Get media from storage folder. Allows setting ACL for assets
     *
     * @param int $mediaId
     * @param string $filename
     * @return BinaryFileResponse
     */
    public function getMedia(int $mediaId, string $path)
    {
        $path = $mediaId . '/' . $path;
        if (Storage::disk('media')->exists($path)) {
            return response()->file(Storage::disk('media')->path($path));
        } else {
            return response()->file(public_path('/img/no-img.png'));
        }
    }
}
