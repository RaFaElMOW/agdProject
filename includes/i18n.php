<?php

function i18n_config() {
    static $config = null;
    if ($config === null) {
        $config = require __DIR__ . '/../lang/config.php';
    }
    return $config;
}

function current_lang() {
    static $lang = null;
    if ($lang !== null) {
        return $lang;
    }
    $config = i18n_config();
    $requested = isset($_COOKIE[$config['cookie']]) ? $_COOKIE[$config['cookie']] : $config['default'];
    $lang = isset($config['languages'][$requested]) ? $requested : $config['fallback'];
    return $lang;
}

function set_current_lang($langCode) {
    $config = i18n_config();
    if (!isset($config['languages'][$langCode])) {
        return false;
    }
    setcookie($config['cookie'], $langCode, time() + $config['cookie_lifetime'], '/');
    return true;
}

function translations() {
    static $cache = [];
    $lang = current_lang();
    if (!isset($cache[$lang])) {
        $config = i18n_config();
        $cache[$lang] = require $config['languages'][$lang]['file'];
    }
    return $cache[$lang];
}

/**
 * Admin-entered overrides (via the panel's translation editor), loaded once per
 * request per language. Falls back to an empty map if the DB isn't reachable yet
 * (e.g. called before the Composer autoloader is available) so t() never breaks
 * the static lang/*.php fallback that's been there since before this existed.
 */
function translation_overrides() {
    static $cache = [];
    $lang = current_lang();
    if (!isset($cache[$lang])) {
        $cache[$lang] = [];
        if (class_exists(\App\Repositories\TranslationRepository::class)) {
            try {
                $cache[$lang] = (new \App\Repositories\TranslationRepository())->overridesFor($lang);
            } catch (\Throwable $e) {
                $cache[$lang] = [];
            }
        }
    }
    return $cache[$lang];
}

function t($key) {
    $overrides = translation_overrides();
    if (isset($overrides[$key]) && $overrides[$key] !== '') {
        return $overrides[$key];
    }

    $dict = translations();
    return isset($dict[$key]) ? $dict[$key] : $key;
}
