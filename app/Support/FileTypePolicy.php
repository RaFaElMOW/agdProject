<?php

namespace App\Support;

/**
 * Single source of truth for "which files are we willing to accept, anywhere in the
 * system". Image fields (photo/cover/banner/logo/favicon/gallery) must only ever use
 * IMAGE_MIMES. DOCUMENT_MIMES is reserved for the day a CRUD needs to accept an actual
 * file (e.g. a book's e-book file) instead of just a link — it is not wired into any
 * upload path yet, but the allowlist is defined now so that feature starts from a
 * reviewed policy instead of an ad-hoc one.
 *
 * Validation must always check the real file signature (finfo), never the client-supplied
 * Content-Type or the filename extension alone — see UploadService.
 */
class FileTypePolicy
{
    /** @var array<string, string> mime => extension */
    public const IMAGE_MIMES = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
    ];

    /** @var array<string, string> mime => extension */
    public const DOCUMENT_MIMES = [
        'application/pdf' => 'pdf',
        'application/msword' => 'doc',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
        'application/vnd.ms-excel' => 'xls',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
    ];

    /** @var array<string, string> mime => extension — full allowlist (images + documents) */
    public const ALL_MIMES = self::IMAGE_MIMES + self::DOCUMENT_MIMES;
}
