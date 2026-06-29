<?php

namespace App\Support;

class Slug
{
    public static function make(string $text): string
    {
        $text = mb_strtolower(trim($text));
        $transliterated = iconv('UTF-8', 'ASCII//TRANSLIT', $text) ?: $text;
        $slug = preg_replace('/[^a-z0-9]+/', '-', $transliterated);
        return trim($slug, '-') ?: bin2hex(random_bytes(4));
    }
}
