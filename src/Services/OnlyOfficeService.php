<?php

namespace sol42\LaravelOnlyoffice\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class OnlyOfficeService
{
    public static function getDocumentMeta(string $docPath): array
    {
        $url = Storage::url($docPath);
        $type = File::extension($docPath);
        $title = Arr::last(explode('/', $docPath));
        $key = encrypt($docPath);

        return [
            'key' => $key,
            'fileType' => $type,
            'title' => $title,
            'url' => $url
        ];
    }

    public static function save(string $fileUrl, string $key): bool
    {
        $contents = file_get_contents($fileUrl);
        $name = decrypt($key);
        return Storage::put($name, $contents);
    }

    public static function  getDocumentKind(string $extension) {
        if(in_array($extension, config('onlyoffice.docExtensions'))) {
            return 'word';
        }

        if(in_array($extension, config('onlyoffice.docExtensions'))) {
            return 'cell';
        }

        if(in_array($extension, config('onlyoffice.docExtensions'))) {
            return 'slide';
        }

        if(in_array($extension, config('onlyoffice.docExtensions'))) {
            return 'pdf';
        }

        return '';
    }
}
