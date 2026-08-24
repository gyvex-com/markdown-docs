<?php

namespace GyvexCom\MarkdownDocs\Http\Controllers;

use GyvexCom\MarkdownDocs\Contracts\MarkdownDocsRepository;
use GyvexCom\MarkdownDocs\Support\DocsConfig;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DocsController
{
    public function show(Request $request, MarkdownDocsRepository $docs, ?string $path = null): View
    {
        $doc = $docs->resolve((string) $path);

        abort_if($doc === null, 404);

        $content = $docs->render($doc);
        $tree = $docs->tree();

        $nav = DocsConfig::menu() ?? $tree;

        $activePath = '/'.ltrim($request->path(), '/');

        $stylesheetUrl = DocsConfig::stylesheet() !== null
            ? route('markdown-docs.stylesheet')
            : null;

        return view('markdown-docs::docs', [
            'content' => $content,
            'title' => $doc->title(),
            'tree' => $nav,
            'activePath' => $activePath,
            'stylesheetUrl' => $stylesheetUrl,
        ]);
    }
}
