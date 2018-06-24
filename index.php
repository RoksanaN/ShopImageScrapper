<?php

require_once 'vendor/autoload.php';

function httpHandler(array $request) {
    if (isset($request['zip']) && is_numeric($request['zip'])) {
        require_once 'zip.php';

        streamZip($request['zip']);

        die;
    }

    if (isset($request['delete_all_zip'])) {
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

    $requestArticle = $request['article'] ?? '';
    $customeUrl = $request['customeUrl'] ?? '';

    $success = false;

    if ($requestArticle) {
        $host = 'http://trade-city.ua/catalog/';
        $category = 'bag/';

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
}

httpHandler($_GET);
