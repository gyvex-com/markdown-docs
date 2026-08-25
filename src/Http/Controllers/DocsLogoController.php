<?php

namespace GyvexCom\MarkdownDocs\Http\Controllers;

use GyvexCom\MarkdownDocs\Support\DocsConfig;
use Illuminate\Http\Response;

class DocsLogoController
{
    protected static array $mimes = [
        'svg' => 'image/svg+xml',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
        'webp' => 'image/webp',
        'avif' => 'image/avif',
        'ico' => 'image/x-icon',
    ];

    public function logo(): Response
    {
        $logo = DocsConfig::brandingLogo();

        abort_if($logo === null, 404);

        $root = rtrim(config('docs.path', base_path('docs')), '/');
        $file = $root.'/'.ltrim($logo, '/');

        $real = realpath($file);
        $realRoot = realpath($root);

        abort_if(
            $real === false
            || !is_file($real)
            || $realRoot === false
            || strncmp($real, $realRoot.DIRECTORY_SEPARATOR, strlen($realRoot.DIRECTORY_SEPARATOR)) !== 0,
            404
        );

        $ext = strtolower(pathinfo($real, PATHINFO_EXTENSION));
        $mime = static::$mimes[$ext] ?? (function_exists('mime_content_type')
            ? mime_content_type($real)
            : 'application/octet-stream');

        return response()->make(
            file_get_contents($real),
            200,
            ['Content-Type' => $mime ?: 'application/octet-stream']
        );
    }
}
