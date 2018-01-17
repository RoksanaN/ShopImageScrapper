<?php

$html = file_get_contents('http://trade-city.ua/catalog/glasses/P5015S-LLY5594.html');

function parseImageUrlList(string $html): array {
    return [

    ];
}

function parseArticle(string $html): string {

}

$imageUrlList = parseImageUrlList($html);

if (empty($imageUrlList)) {
    throw new \Exception('Need update parse function');
}

$article = parseArticle($html);
mkdir($article, 0777);

foreach ($imageUrlList as $imageUrl) {
    $imageBaseName = basename($imageUrl);

    $imageContent = file_get_contents($imageUrl);

    file_put_contents('./' . $article . '/' . $imageBaseName, $imageContent);
}
