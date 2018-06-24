<?php

require_once 'vendor/autoload.php';

function httpHandler(array $request) {
    if (isset($request['zip']) && is_numeric($request['zip'])) {
        require_once 'zip.php';

        streamZip($request['zip']);

        return;
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

        return;
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
        $scrapper = new Scrapper();
        try {
            foreach ($articles as $article) {
                try {
                    $scrapper->scrap($host . $category . $article . '.html', $prefix);
                } catch (\Exception $e) {
                    // NOP
                }
            }

            $redirect = str_replace('index.php', '', $_SERVER['PHP_SELF']);
            header('Location: ' . $redirect . '?customeUrl=' . $customeUrl);
            return;
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
