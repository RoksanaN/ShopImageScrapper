<?php

require_once 'vendor/autoload.php';

if (isset($_GET['zip']) && is_numeric($_GET['zip'])) {
    require_once 'zip.php';

    streamZip($_GET['zip']);

    die;
}

if (isset($_GET['delete_all_zip'])) {
    $handle = opendir('result');
    $fs = new \Symfony\Component\Filesystem\Filesystem();

    while (false !== ($entry = readdir($handle))) {
        if ($entry !== "." && $entry !== ".." && $entry !== 'index.php') {
            $fs->remove('result' . DIRECTORY_SEPARATOR . $entry);
        }
    }

    header('Location: /');

    die;
}

$requestArticle = $_GET['article'] ?? '';
$customeUrl = $_GET['customeUrl'] ?? '';

$success = false;

$host = 'http://trade-city.ua/catalog/';
$category = 'bag/';

if ($requestArticle) {
    require_once 'scrap.php';

    $articles = array_filter(explode(' ', $requestArticle));

    $prefix = time();
    try {
        foreach ($articles as $article) {
            try {
                scrap($host . $category . $article . '.html', $prefix);
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
