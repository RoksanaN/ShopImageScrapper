<?php

require_once 'vendor/autoload.php';

use Symfony\Component\DomCrawler\Crawler;

$html = file_get_contents('http://trade-city.ua/catalog/glasses/P5015S-LLY5594.html');

function parseImageUrlList(string $html): array {
    $crawler = new Crawler($html);

    $images = $crawler->filter('.slider-inner li a img');

    return $images->each(function (Crawler $image) {
        return $image->attr('src');
    });
}

function parseArticle(string $html): string {
    $crawler = new Crawler($html);

    return $crawler->filter('.articul')->attr('data-value');
}

$imageUrlList = parseImageUrlList($html);

if (empty($imageUrlList)) {
    throw new \Exception('Need update parse function');
}

file_exists('result') || mkdir('result', 0777);

$article = parseArticle($html);

$targetDirectory = "result/$article";
file_exists($targetDirectory) || mkdir($targetDirectory, 0777);

foreach ($imageUrlList as $imageUrl) {
    $imageBaseName = basename($imageUrl);

    $imageContent = file_get_contents('http://trade-city.ua' . $imageUrl);

    file_put_contents('./result/' . $article . '/' . $imageBaseName, $imageContent);
}
