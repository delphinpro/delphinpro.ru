<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023-2024.
 */

namespace Database\Seeders;

use DirectoryIterator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Orchid\Attachment\File;
use Orchid\Attachment\Models\Attachment;
use RuntimeException;

class Seed
{
    public static string $DIR_ASSETS = 'database/seeders/assets';

    private static array $usedFiles = [];

    public static function randomFile($sourceDirectory, $absolute = false, $reset = false): string
    {

        $sourceDirectory = self::$DIR_ASSETS.'/'.$sourceDirectory;

        if (!is_dir($sourceDirectory)) {
            throw new RuntimeException(__METHOD__.': source in not directory: '.$sourceDirectory);
        }

        if (!array_key_exists($sourceDirectory, self::$usedFiles)) {
            self::$usedFiles[$sourceDirectory] = [];
        }

        if ($reset) {
            self::$usedFiles[$sourceDirectory] = [];
        }

        $files = [];

        /** @var \DirectoryIterator $fileInfo */
        foreach (new DirectoryIterator($sourceDirectory) as $fileInfo) {
            if ($fileInfo->isDot()) continue;
            if ($fileInfo->isDir()) continue;
            if ($fileInfo->isFile() && $fileInfo->isReadable()) {
                $files[] = $fileInfo->getFilename();
            }
        }

        $unused = array_values(array_diff($files, self::$usedFiles[$sourceDirectory]));

        if (!count($unused)) {
            self::$usedFiles[$sourceDirectory] = [];
            $unused = $files;
        }

        $index = mt_rand(0, count($unused) - 1);

        $filename = $unused[$index];
        self::$usedFiles[$sourceDirectory][] = $filename;

        return $absolute ? $sourceDirectory.DIRECTORY_SEPARATOR.$filename : $filename;
    }

    /**
     * @throws \League\Flysystem\FilesystemException
     */
    public static function loadFile(string $filename): Attachment
    {
        $file = new UploadedFile(base_path(self::$DIR_ASSETS.'/'.trim($filename, '/')), basename($filename));

        return (new File($file))->load();
    }

    /**
     * @throws \League\Flysystem\FilesystemException
     * @throws \Exception
     */
    public static function loadRandomFile(string $dir): Attachment
    {
        $cover = self::randomFile($dir, absolute: false);

        return self::loadFile($dir.'/'.$cover);
    }

    public static function copyPublicFile(string $sourceFile, string $targetDir): string
    {
        $source = self::$DIR_ASSETS.'/'.$sourceFile;

        if (!is_file($source)) {
            throw new RuntimeException(__METHOD__.': source in not a file: '.$source);
        }

        $file = pathinfo($source);
        $destination = $targetDir.'/'.substr(md5(date('Y/m')), 0, 8).'/';
        $targetFile = $destination.Str::slug($file['filename']).'.'.$file['extension'];

        Storage::disk('public')->makeDirectory($destination);

        copy(base_path($source), Storage::disk('public')->path($targetFile));

        return Storage::url($targetFile);
    }

    public static function copyRandomPublicFile(string $sourceDir, string $targetDir): string
    {
        $source = self::randomFile($sourceDir, true);

        $file = pathinfo($source);
        $destination = $targetDir.'/'.substr(md5(date('Y/m')), 0, 8).'/';
        $targetFile = $destination.Str::slug($file['filename']).'.'.$file['extension'];

        Storage::disk('public')->makeDirectory($destination);

        copy(base_path($source), Storage::disk('public')->path($targetFile));

        return Storage::url($targetFile);
    }

    public static function getContent(string $filename, ?array $placeholders = null): string
    {
        $content = file_get_contents(base_path(self::$DIR_ASSETS.'/'.trim($filename, '/')));

        if ($placeholders) {
            $content = __($content, $placeholders);
        }

        return $content;
    }
}
