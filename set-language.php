<?php

require_once __DIR__ . '/includes/i18n.php';

if (isset($_GET['lang'])) {
    set_current_lang($_GET['lang']);
}

$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'index.php';
if (!preg_match('/^[a-zA-Z0-9_-]+\.php$/', $redirect)) {
    $redirect = 'index.php';
}

header('Location: ' . $redirect);
exit;
