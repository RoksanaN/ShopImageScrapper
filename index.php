<?php

require_once 'vendor/autoload.php';

$url = $_GET['url'] ?? '';
$customeUrl = $_GET['customeUrl'] ?? '';

$success = false;

if ($url) {
    require_once 'scrap.php';

    try {
        scrap($url);
        $redirect = str_replace('index.php', '', $_SERVER['PHP_SELF']);
        header('Location: ' . $redirect . '?customeUrl=' . $customeUrl);
        exit();
    } catch (\Exception $e) {
        // NOP
    }
}
if ($customeUrl) {
	$success = true;
}

require_once 'index.html.php';
