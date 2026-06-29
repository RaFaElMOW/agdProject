<?php

namespace App\Core;

use App\Support\BasePath;
use App\Support\UrlAllowlist;

class Response
{
    public static function redirect(string $path): never
    {
        $safe = UrlAllowlist::sanitizeInternal($path);
        header('Location: ' . BasePath::url($safe));
        exit;
    }

    public static function json(array $data, int $status = 200): never
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public static function status(int $status): void
    {
        http_response_code($status);
    }

    public static function forbidden(string $message = 'Acesso negado.'): never
    {
        http_response_code(403);
        echo $message;
        exit;
    }

    public static function notFound(string $message = 'Não encontrado.'): never
    {
        http_response_code(404);
        echo $message;
        exit;
    }
}
