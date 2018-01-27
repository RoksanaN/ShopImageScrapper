<?php

require_once 'vendor/autoload.php';

$article = $_GET['article'] ?? '';
$customeUrl = $_GET['customeUrl'] ?? '';

$success = false;

$host = 'http://trade-city.ua/catalog/';
$categories = [
    // use only one variant because server return the same result for any category
    'bag/',
//    'belts/',
//    'glasses/',
];

if ($article) {
    require_once 'scrap.php';

    try {
        foreach ($categories as $category) {
            try {
                scrap($host . $category . $article . '.html');
            } catch (\Exception $e) {
                // NOP
            }
        }

        $redirect = str_replace('index.php', '', $_SERVER['PHP_SELF']);
        header('Location: ' . $redirect . '?customeUrl=' . $customeUrl);
        die;
    } catch (\Exception $e) {
        // NOP
    }
}
if ($customeUrl) {
	$success = true;
}

require_once 'index.html.php';
