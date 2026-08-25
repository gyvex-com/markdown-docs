<?php

namespace GyvexCom\MarkdownDocs\Support;

use Symfony\Component\Yaml\Yaml;

class DocsConfig
{
    protected static ?array $cache = null;

    protected static ?string $cacheKey = null;

    public static function path(): string
    {
        return rtrim(config('docs.path', base_path('docs')), '/')
            .'/'.ltrim(config('docs.config_file', '.docs.yaml'), '/');
    }

    public static function exists(): bool
    {
        return is_file(static::path());
    }

    public static function data(): array
    {
        $file = static::path();
        $mtime = static::exists() ? filemtime($file) : 0;
        $key = $file.'@'.$mtime;

        if ($key === static::$cacheKey && static::$cache !== null) {
            return static::$cache;
        }

        static::$cacheKey = $key;
        static::$cache = static::exists()
            ? (Yaml::parseFile($file) ?: [])
            : [];

        if (!is_array(static::$cache)) {
            static::$cache = [];
        }

        return static::$cache;
    }

    public static function get(string $key, $default = null)
    {
        return \Illuminate\Support\Arr::get(static::data(), $key, $default);
    }

    public static function brandingLogo(): ?string
    {
        $value = static::get('logo');

        if (is_string($value) && $value !== '') {
            return $value;
        }

        $config = config('docs.branding.logo');

        return is_string($config) && $config !== '' ? $config : null;
    }

    public static function brandingText(): string
    {
        $value = static::get('text');

        if (is_string($value) && $value !== '') {
            return $value;
        }

        $config = config('docs.branding.text');

        if (is_string($config) && $config !== '') {
            return $config;
        }

        return ucfirst(config('docs.route_prefix', 'docs'));
    }

    public static function brandingLogoUrl(): ?string
    {
        $logo = static::brandingLogo();

        if ($logo === null) {
            return null;
        }

        if (preg_match('#^(https?:)?//#i', $logo) || str_starts_with($logo, '/')) {
            return $logo;
        }

        return route('markdown-docs.logo');
    }

    public static function stylesheet(): ?string
    {
        $value = static::get('stylesheet');

        return is_string($value) && $value !== '' ? $value : null;
    }

    public static function menu(): ?array
    {
        $menu = static::get('menu');

        if (!is_array($menu) || count($menu) === 0) {
            return null;
        }

        return static::normalizeMenu($menu);
    }

    protected static function normalizeMenu(array $items): array
    {
        $nodes = [];

        foreach ($items as $item) {
            if (!is_array($item)) {
                continue;
            }

            $children = isset($item['children']) && is_array($item['children'])
                ? static::normalizeMenu($item['children'])
                : [];

            $nodes[] = [
                'title' => (string) ($item['title'] ?? 'Untitled'),
                'url' => (string) ($item['url'] ?? '#'),
                'children' => $children,
            ];
        }

        return $nodes;
    }
}
