<?php

namespace GyvexCom\MarkdownDocs\Http\Controllers;

use GyvexCom\MarkdownDocs\Support\DocsConfig;
use Illuminate\Http\Response;

class DocsStylesheetController
{
    public function stylesheet(): Response
    {
        $stylesheet = DocsConfig::stylesheet();

        abort_if($stylesheet === null, 404);

        $root = rtrim(config('docs.path', base_path('docs')), '/');
        $file = $root.'/'.ltrim($stylesheet, '/');

        $real = realpath($file);
        $realRoot = realpath($root);

        abort_if(
            $real === false
            || !is_file($real)
            || $realRoot === false
            || strncmp($real, $realRoot.DIRECTORY_SEPARATOR, strlen($realRoot.DIRECTORY_SEPARATOR)) !== 0,
            404
        );

        return response()->make(
            file_get_contents($real),
            200,
            ['Content-Type' => 'text/css']
        );
    }
}
