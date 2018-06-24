<?php

use Symfony\Component\DomCrawler\Crawler;

class ScrapperTradeCity
{
    public function scrap(string $article, string $prefix)
    {
        $articleUrl = $this->generateArticleUrl($article);

        $html = file_get_contents($articleUrl);

        $articleUrlParsed = parse_url($articleUrl);

        $articleUrl = "{$articleUrlParsed['scheme']}://{$articleUrlParsed['host']}";

        $crawler = new Crawler($html);
        $imageUrlList = $this->parseImageUrlList($crawler);

        if (empty($imageUrlList)) {
            throw new \Exception('Need update parse function');
        }

        $article = $this->parseArticle($crawler);
        $targetDirectory = 'result' . DIRECTORY_SEPARATOR . $prefix;
        file_exists($targetDirectory) || mkdir($targetDirectory, 0777, true);

        $count = 1;

        foreach ($imageUrlList as $imageUrl) {
            $imageBaseName = basename($imageUrl);

            $extension = $ext = pathinfo($imageBaseName, \PATHINFO_EXTENSION);
            $imageName = "{$article}_{$count}.{$extension}";
            ++$count;

            $imageContent = file_get_contents($articleUrl . $imageUrl);

            file_put_contents('.' . DIRECTORY_SEPARATOR . $targetDirectory . DIRECTORY_SEPARATOR . $imageName, $imageContent);
        }
    }

    public function parseImageUrlList(Crawler $crawler): array
    {
        $images = $crawler->filter('.slider-inner li a img');

        return $images->each(function (Crawler $image) {
            return $image->attr('src');
        });
    }

    public function parseArticle(Crawler $crawler): string
    {
        return $crawler->filter('.articul')->attr('data-value');
    }

    private function generateArticleUrl(string $article): string
    {
        $host = 'http://trade-city.ua/catalog/';

        $category = 'bag/';

        return $host . $category . $article . '.html';
    }
}

