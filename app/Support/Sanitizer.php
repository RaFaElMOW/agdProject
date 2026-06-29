<?php

namespace App\Support;

use HTMLPurifier;
use HTMLPurifier_Config;

/**
 * Sanitizes admin-authored rich text (currently: blog post body) before it's stored,
 * so even a compromised/careless admin account can't persist a stored-XSS payload.
 */
class Sanitizer
{
    private static ?HTMLPurifier $purifier = null;

    public static function richText(string $html): string
    {
        if (self::$purifier === null) {
            $config = HTMLPurifier_Config::createDefault();
            $config->set('HTML.Allowed', 'p,br,strong,em,b,i,u,ul,ol,li,a[href],img[src|alt],h2,h3,h4,blockquote,span');
            $config->set('HTML.TargetBlank', true);
            $config->set('URI.AllowedSchemes', ['http' => true, 'https' => true, 'mailto' => true]);
            $config->set('Cache.SerializerPath', sys_get_temp_dir());
            self::$purifier = new HTMLPurifier($config);
        }

        return self::$purifier->purify($html);
    }
}
