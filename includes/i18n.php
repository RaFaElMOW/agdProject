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

function t($key) {
    $dict = translations();
    return isset($dict[$key]) ? $dict[$key] : $key;
}
