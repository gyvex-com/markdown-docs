<?php

namespace GyvexCom\MarkdownDocs;

use GyvexCom\MarkdownDocs\Contracts\MarkdownDocsRepository;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \GyvexCom\MarkdownDocs\Doc|null resolve(string $path)
 * @method static string render(\GyvexCom\MarkdownDocs\Doc $doc)
 * @method static array tree()
 *
 * @see \GyvexCom\MarkdownDocs\MarkdownDocsManager
 */
class MarkdownDocs extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'markdown-docs';
    }
}
