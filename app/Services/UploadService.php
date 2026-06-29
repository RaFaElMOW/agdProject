<?php

namespace App\Services;

use App\Support\FileTypePolicy;
use RuntimeException;

/**
 * Validates and stores image uploads: real MIME via finfo (not the client-sent
 * Content-Type), extension allowlist, size cap, dimension cap (decompression-bomb
 * guard), randomized filename, and a GD re-encode pass that strips any payload
 * smuggled inside image metadata/bytes (defeats the classic "polyglot JPEG/PNG with
 * embedded PHP" upload bypass).
 *
 * Only App\Support\FileTypePolicy::IMAGE_MIMES is accepted here — every upload field in
 * the admin panel is an image field (photo/cover/banner/logo/favicon/gallery). Document
 * uploads (PDF/DOC/XLS) are a deliberately separate, not-yet-built code path: GD can't
 * re-encode a document the way it can an image, so accepting one here would mean storing
 * a file we can only MIME-sniff, not sanitize — a different (and weaker) security
 * guarantee that must be a conscious choice when that feature is actually built.
 */
class UploadService
{
    private const MAX_BYTES = 4 * 1024 * 1024; // 4MB
    private const MAX_DIMENSION_PX = 8000; // guards against decompression-bomb-style images

    private string $destinationDir;

    public function __construct(string $destinationDir)
    {
        $this->destinationDir = rtrim($destinationDir, '/');
        if (!is_dir($this->destinationDir)) {
            mkdir($this->destinationDir, 0755, true);
        }
    }

    /**
     * @param array $file One entry from $_FILES
     * @return string Public relative path (e.g. "uploads/branding/ab12cd.jpg")
     */
    public function storeImage(array $file): string
    {
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            throw new RuntimeException('Falha no envio do arquivo.');
        }

        if ($file['size'] > self::MAX_BYTES) {
            throw new RuntimeException('Arquivo maior que o limite permitido (4MB).');
        }

        if (!is_uploaded_file($file['tmp_name'])) {
            throw new RuntimeException('Upload inválido.');
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);

        if (!isset(FileTypePolicy::IMAGE_MIMES[$mime])) {
            throw new RuntimeException('Tipo de arquivo não permitido. Use apenas JPG ou PNG.');
        }

        $dimensions = @getimagesize($file['tmp_name']);
        if ($dimensions === false) {
            throw new RuntimeException('Não foi possível ler a imagem enviada.');
        }
        if ($dimensions[0] > self::MAX_DIMENSION_PX || $dimensions[1] > self::MAX_DIMENSION_PX) {
            throw new RuntimeException('Imagem com dimensões muito grandes (máximo ' . self::MAX_DIMENSION_PX . 'px).');
        }

        $extension = FileTypePolicy::IMAGE_MIMES[$mime];
        $image = $this->readImage($file['tmp_name'], $mime);

        $filename = bin2hex(random_bytes(16)) . '.' . $extension;
        $destination = $this->destinationDir . '/' . $filename;

        $this->writeImage($image, $destination, $mime);
        imagedestroy($image);

        return $filename;
    }

    private function readImage(string $path, string $mime): \GdImage
    {
        $image = match ($mime) {
            'image/jpeg' => imagecreatefromjpeg($path),
            'image/png' => imagecreatefrompng($path),
            default => false,
        };

        if ($image === false) {
            throw new RuntimeException('Não foi possível processar a imagem enviada.');
        }

        return $image;
    }

    private function writeImage(\GdImage $image, string $destination, string $mime): void
    {
        $ok = match ($mime) {
            'image/jpeg' => imagejpeg($image, $destination, 85),
            'image/png' => imagepng($image, $destination, 6),
            default => false,
        };

        if (!$ok) {
            throw new RuntimeException('Não foi possível salvar a imagem processada.');
        }
    }
}
