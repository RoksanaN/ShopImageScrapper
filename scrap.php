<?php

use Symfony\Component\DomCrawler\Crawler;

abstract class Scrapper
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
            $imageBaseName = basename(parse_url($imageUrl)['path']);

            $extension = $ext = pathinfo($imageBaseName, \PATHINFO_EXTENSION);
            $imageName = "{$article}_{$count}.{$extension}";
            ++$count;

            $contentUrl = $imageUrl;

            if (strpos($contentUrl, $articleUrlParsed['host']) === false) {
                $contentUrl = $articleUrl . $imageUrl;
            }

            $imageContent = file_get_contents($contentUrl);

            file_put_contents('.' . DIRECTORY_SEPARATOR . $targetDirectory . DIRECTORY_SEPARATOR . $imageName, $imageContent);
        }
    }

    abstract protected function parseImageUrlList(Crawler $crawler): array;
    abstract protected function parseArticle(Crawler $crawler): string;
    abstract protected function generateArticleUrl(string $article): string;
}

class ScrapperTradeCity extends Scrapper
{
    protected function parseImageUrlList(Crawler $crawler): array
    {
        $images = $crawler->filter('.slider-inner li a img');

        return $images->each(function (Crawler $image) {
            return $image->attr('src');
        });
    }

    protected function parseArticle(Crawler $crawler): string
    {
        return $crawler->filter('.articul')->attr('data-value');
    }

    protected function generateArticleUrl(string $article): string
    {
        $host = 'http://trade-city.ua/catalog/';

        $category = 'bag/';

        return $host . $category . $article . '.html';
    }
}

class ScrapperAdidas extends Scrapper
{
    protected function parseImageUrlList(Crawler $crawler): array
    {
        $images = $crawler->filter('.image-carousel-container ul li img');

        return $images->each(function (Crawler $image) {
            return $image->attr('data-zoom');
        });
    }

    protected function parseArticle(Crawler $crawler): string
    {
        return $crawler->filter('#main-section')->attr('data-sku');
    }

    protected function generateArticleUrl(string $article): string
    {
        $host = 'https://www.adidas.ru/';

        $category = 'krossovki-dlia-bega-pureboost-x/';

        return $host . $category . $article . '.html';
    }
}
