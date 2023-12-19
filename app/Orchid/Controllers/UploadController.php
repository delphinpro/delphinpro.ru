<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023.
 */

namespace App\Orchid\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Routing\Controller;
use Orchid\Attachment\File;

class UploadController extends Controller
{
    public function upload(Request $request): JsonResponse
    {
        /** @var \Orchid\Attachment\Models\Attachment $attachment */
        $attachment = collect($request->allFiles())
            ->flatten()
            ->map(fn(UploadedFile $file) => resolve(File::class, [
                'file'  => $file,
                'disk'  => 'public',
                'group' => 'content',
            ])->load())
            ->first();

        return response()->json([
            'location' => $attachment->url(),
        ]);
    }
}
