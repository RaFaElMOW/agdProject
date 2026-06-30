<?php

namespace App\Support;

use HTMLPurifier;
use HTMLPurifier_Config;

/**
 * Sanitizes admin-authored rich text (currently: blog post body) before it's stored,
 * so even a compromised/careless admin account can't persist a stored-XSS payload.
 *
 * This is the ONLY security boundary for blog content — the Quill editor in the admin
 * form is a client-side convenience (it can be bypassed entirely by posting raw HTML
 * directly to the endpoint), so the allowlist here must independently reject anything
 * dangerous regardless of what the editor's toolbar does or doesn't expose.
 *
 * The allowlist intentionally matches 1:1 the toolbar buttons wired in blog-form.php —
 * no `style` or `class` attribute is allowed on anything, which rules out the inline-CSS
 * and data-URI-via-CSS XSS tricks without needing a CSS property allowlist at all.
 */
class Sanitizer
{
    private static ?HTMLPurifier $purifier = null;

    public static function richText(string $html): string
    {
        if (self::$purifier === null) {
            $config = HTMLPurifier_Config::createDefault();
            $config->set('HTML.Allowed', 'p,br,strong,em,b,i,u,s,ul,ol,li,a[href],img[src|alt],h2,h3,h4,blockquote,span');
            $config->set('HTML.TargetBlank', true);
            // Defaults to true in this HTMLPurifier version, but set explicitly so this
            // doesn't silently regress if the dependency is ever upgraded/downgraded:
            // every target="_blank" link this allows also gets rel="noopener noreferrer".
            $config->set('HTML.TargetNoopener', true);
            $config->set('HTML.TargetNoreferrer', true);
            $config->set('URI.AllowedSchemes', ['http' => true, 'https' => true, 'mailto' => true]);
            $config->set('Cache.SerializerPath', sys_get_temp_dir());
            self::$purifier = new HTMLPurifier($config);
        }

        return self::$purifier->purify($html);
    }
}
