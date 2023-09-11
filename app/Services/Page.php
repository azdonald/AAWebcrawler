<?php

namespace App\Services;

class Page
{
    private string $title;
    private string $url;
    private int $statusCode;
    private int $wordCount;
    private float $loadTime;
    private array $urls;

    private array $images;
    private array $externalLinks;

    /**
     * @return array
     */
    public function getExternalLinks(): array
    {
        return $this->externalLinks;
    }

    /**
     * @param array $externalLinks
     */
    public function setExternalLinks(array $externalLinks): void
    {
        $this->externalLinks = array_unique($externalLinks);
    }

    /**
     * @return array
     */
    public function getInternalLinks(): array
    {
        return $this->internalLinks;
    }

    /**
     * @param array $internalLinks
     */
    public function setInternalLinks(array $internalLinks): void
    {
        $this->internalLinks = array_unique($internalLinks);
    }
    private array $internalLinks;


    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }


    /**
     * @return array
     */
    public function getImages(): array
    {
        return $this->images;
    }

    /**
     * @param array $images
     */
    public function setImages(array $images): void
    {
        $this->images = $images;
    }

    /**
     * @return array
     */
    public function getUrls(): array
    {
        return $this->urls;
    }

    /**
     * @param array $urls
     */
    public function setUrls(array $urls): void
    {
        $this->urls = $urls;
        $external = array();
        $internal = array();
        foreach ($this->urls as $url) {
                //var_dump($url);
            if ($this->isInternalLink($url)) {
                //var_dump("internal");
                $internal[] = $url;
            } else{
                $external[] = $url;
            }
        }
        $this->setExternalLinks($external);
        $this->setInternalLinks($internal);
    }



    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     */
    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    /**
     * @return int
     */
    public function getWordCount(): int
    {
        return $this->wordCount;
    }

    /**
     * @param int $wordCount
     */
    public function setWordCount(int $wordCount): void
    {
        $this->wordCount = $wordCount;
    }

    /**
     * @return int
     */
    public function getLoadTime(): float
    {
        return $this->loadTime;
    }

    /**
     * @param float $loadTime
     */
    public function setLoadTime(float $loadTime): void
    {
        $this->loadTime = $loadTime;
    }


    private function isInternalLink(string $link) :bool
    {
        if (str_starts_with($link, '/') || str_starts_with($link, '#') || empty($link)) {
            return true;
        }
        $host = Page::getURLHost($this->getUrl());

        return Page::getURLHost($link)== $host;
    }

    private static function getURLHost (string $url): ?string
    {

        $host = parse_url($url, PHP_URL_HOST);
        $domains = explode('.', $host);
        if (count($domains) > 2){
            return implode(".", array_slice($domains, -2));
        }
        return $host;
    }




}
