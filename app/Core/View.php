<?php

namespace App\Core;

class View
{
    private const BASE_PATH = __DIR__ . '/../../resources/views/';

    public static function render(string $template, array $data = [], ?string $layout = null): string
    {
        $content = self::renderTemplate($template, $data);

        if ($layout !== null) {
            $content = self::renderTemplate($layout, $data + ['content' => $content]);
        }

        return $content;
    }

    public static function output(string $template, array $data = [], ?string $layout = null): void
    {
        echo self::render($template, $data, $layout);
    }

    private static function renderTemplate(string $template, array $data): string
    {
        $path = self::BASE_PATH . $template . '.php';
        if (!is_file($path)) {
            throw new \RuntimeException("View not found: {$template}");
        }

        $renderer = static function (string $__path, array $__data): string {
            extract($__data, EXTR_SKIP);
            ob_start();
            require $__path;
            return (string) ob_get_clean();
        };

        return $renderer($path, $data);
    }
}
