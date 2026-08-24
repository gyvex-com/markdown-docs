<?php

namespace GyvexCom\MarkdownDocs\Support;

use Symfony\Component\Yaml\Yaml;

class FrontMatter
{
    /**
     * Split raw markdown into [metadata, body].
     *
     * Supports a leading YAML block delimited by `---` lines:
     *
     *   ---
     *   title: Hello
     *   order: 1
     *   ---
     *   # Body...
     *
     * Returns empty metadata when no delimiters are present.
     *
     * @return array{0: array, 1: string}
     */
    public static function parse(string $content): array
    {
        $content = preg_replace('/\r\n/', "\n", $content);
        $content = ltrim($content);

        if (!str_starts_with($content, '---')) {
            return [[], $content];
        }

        $lines = explode("\n", $content, 2);

        // A `---` not on its own first line (e.g. a horizontal rule mid-doc) is
        // not front matter; treat as plain content.
        if (($lines[0] ?? '') !== '---') {
            return [[], $content];
        }

        $rest = $lines[1] ?? '';
        $end = strpos($rest, "\n---");

        if ($end === false) {
            return [[], $content];
        }

        $yaml = substr($rest, 0, $end);
        $body = substr($rest, $end + strlen("\n---"));
        $body = ltrim($body, "\n");

        $metadata = Yaml::parse(trim($yaml)) ?: [];

        if (!is_array($metadata)) {
            $metadata = [];
        }

        return [$metadata, $body];
    }
}
