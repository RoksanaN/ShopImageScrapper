<?php

use Symfony\Component\DomCrawler\Crawler;

function parseImageUrlList(Crawler $crawler): array {
    $images = $crawler->filter('.slider-inner li a img');

    return $images->each(function (Crawler $image) {
        return $image->attr('src');
    });
}

function parseArticle(Crawler $crawler): string {
    return $crawler->filter('.articul')->attr('data-value');
}

function scrap(string $articleUrl) {
    $html = file_get_contents($articleUrl);

    $articleUrlParsed = parse_url($articleUrl);

    $articleUrl = "{$articleUrlParsed['scheme']}://{$articleUrlParsed['host']}";

    $crawler = new Crawler($html);
    $imageUrlList = parseImageUrlList($crawler);

    if (empty($imageUrlList)) {
        throw new \Exception('Need update parse function');
    }

    file_exists('result') || mkdir('result', 0777);

    $article = parseArticle($crawler);

    $targetDirectory = "result/$article";
    file_exists($targetDirectory) || mkdir($targetDirectory, 0777);

    $count = 1;

    foreach ($imageUrlList as $imageUrl) {
        $imageBaseName = basename($imageUrl);

        $extension = $ext = pathinfo($imageBaseName, \PATHINFO_EXTENSION);
        $imageName = "{$article}_{$count}.{$extension}";
        ++$count;

        $imageContent = file_get_contents($articleUrl . $imageUrl);

        file_put_contents('./result/' . $article . '/' . $imageName, $imageContent);
    }
}
