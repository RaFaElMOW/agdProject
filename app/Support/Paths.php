<?php

namespace App\Support;

class Paths
{
    public static function root(): string
    {
        return rtrim(dirname(__DIR__, 2), '/\\');
    }

    public static function uploads(string $sub = ''): string
    {
        return self::root() . '/uploads' . ($sub !== '' ? '/' . trim($sub, '/') : '');
    }
}
