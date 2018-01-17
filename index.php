<?php

require_once 'vendor/autoload.php';

$url = $_GET['url'] ?? '';

$success = false;

if ($url) {
    require_once 'scrap.php';

    try {
        scrap($url);
        $success = true;
    } catch (\Exception $e) {
        // NOP
    }
}

require_once 'index.html.php';
