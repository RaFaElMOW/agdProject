<?php

/**
 * Defaults seeded once. Values that previously only existed as hardcoded markup are
 * corrected here to the real AGD Niger data already used on contact.php — the template's
 * placeholder lorem ("203 Fake St...", "info@yourdomain.com") is not "current content"
 * worth preserving. Branding fields (logo/favicon/colors) are pre-filled with the exact
 * assets/colors the static theme already uses today (images/logo_AGD.png for the
 * "scrolled" navbar state, images/logo_AGD_white.png for the default dark-navbar state,
 * a generated favicon, and the real primary/secondary colors already in style.css) —
 * so making them admin-editable doesn't change a single pixel until someone overrides them.
 */
return [
    'settings' => [
        'site_name' => ['AGD Niger', 'identity'],
        'site_logo' => ['images/logo_AGD.png', 'identity'],
        'site_logo_white' => ['images/logo_AGD_white.png', 'identity'],
        'site_favicon' => ['images/favicon.png', 'identity'],
        'color_primary' => ['#f96d00', 'branding'],
        'color_secondary' => ['#343a40', 'branding'],
        'stat_children_served' => ['1432805', 'content'],
        'social_facebook' => ['https://www.facebook.com/agdniger', 'social'],
        'social_instagram' => ['https://www.instagram.com/agdniger', 'social'],
        'social_twitter' => ['https://twitter.com/xandniger', 'social'],
        'social_youtube' => ['https://www.youtube.com/alexandrecanhoni', 'social'],
        'social_spotify' => ['https://open.spotify.com/artist/2XdJcd6XJApRFv57Pj6HGf', 'social'],
        'social_whatsapp' => ['https://api.whatsapp.com/send?phone=5511965714533', 'social'],
        'contact_address' => ['AGD Níger, BP. 13.801, Niamey - Níger', 'contact'],
        'contact_whatsapp_display' => ['+55 11 96571-4533', 'contact'],
        'contact_email' => ['comunicacao@agdniger.com', 'contact'],
        'contact_phone' => ['', 'contact'],
        'seo_meta_title' => ['AGD Niger', 'seo'],
        'seo_meta_description' => ['Associação Guerreiros de Deus - Níger. Doação, apadrinhamento e projetos sociais.', 'seo'],
        'seo_og_image' => ['', 'seo'],
        'ga_id' => ['', 'analytics'],
        'gtm_id' => ['', 'analytics'],
    ],

    // [location, label, url, parent_label_or_null, sort_order, target_blank]
    'menus' => [
        ['header', 'Início', 'index.php', null, 1, 0],
        ['header', 'Sobre', 'about.php', null, 2, 0],
        ['header', 'Doar', 'donate.php', null, 3, 0],
        ['header', 'Mídia', 'gallery.php', null, 4, 0],
        ['header', 'Apadrinhar', 'apadrinhar.php', null, 5, 0],
        ['header', 'Livros', 'event.php', null, 6, 0],
        ['header', 'Contato', 'contact.php', null, 7, 0],
        ['header', 'Mais', '#', null, 8, 0],
        ['header', 'Quem Somos', 'quemsomos.php', 'Mais', 1, 0],
        ['header', 'Projetos', 'projetos.php', 'Mais', 2, 0],

        ['footer', 'Início', 'index.php', null, 1, 0],
        ['footer', 'Sobre', 'about.php', null, 2, 0],
        ['footer', 'Doar', 'donate.php', null, 3, 0],
        ['footer', 'Causas', 'causes.php', null, 4, 0],
        ['footer', 'Eventos', 'event.php', null, 5, 0],
        ['footer', 'Blog', 'blog.php', null, 6, 0],
    ],

    // Intentionally no `content` defaults: about.php/quemsomos.php keep rendering their
    // current translated (t()) text until an admin saves the CMS form for the first time —
    // at that point a site_content row is created and takes over for the fields it sets.
];
