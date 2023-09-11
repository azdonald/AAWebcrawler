<?php
declare(strict_types=1);

namespace App\Services;

class Crawler {

    public NetworkRequests $network;
    public Parsers $parser;

    public array $pages;

    private array $crawledUrls;

    private string $hostDomain;

    private string $hostScheme;


    function __construct($network, $parser) {
        $this->network = $network;
        $this->parser = $parser;
        $this->pages = [];
        $this->hostDomain = "";
        $this->hostScheme = "";
    }


    function crawl(string $url, $maxCrawl= 6): array
    {
        if ($maxCrawl <= 0) {
            return $this->pages;
        }
        if ($this->isValidUlr($url)) {
            $time_start = microtime(true);
            $req = $this->network->get($url);
            $time_end = microtime(true);
            $this->addToCrawledUrls($url);
            $domain  =$this->getHost($url);
            $scheme = $this->getScheme($url);
            $this->setHostDomain($domain);
            $this->setHostScheme($scheme);

            $p = $this->convertToPage($req->body(), $url);
            $p->setLoadTime($time_end - $time_start);
            $p->setStatusCode($req->status());
            $this->pages [] = $p;
            foreach ($p->getUrls() as $u) {
                $u = $this->buildUrl($u);
                if (!$this->isValidUlr($u) || $this->getHost($u) != $this->getHostDomain() || $this->hasBeenCrawled($u) ) {
                    continue;
                }

                return $this->crawl($u, $maxCrawl -1);
            }
            return $this->pages;
        }
       return $this->pages;

    }

    private function convertToPage(string $body, string $url): Page
    {
        $urls = $this->parser->parseRequest($body, "url");
        $images = $this->parser->parseRequest($body, "image");
        $title = $this->parser->parseRequest($body, "title");

        $p = new Page();
        $p->setUrl($url);
        $p->setTitle($title[0]);
        $p->setImages($images);
        $p->setUrls($urls);
        $p->setWordCount(strlen($body));


        return $p;
    }

    function isValidUlr(string $url): bool {
        if(strlen($url) <= 2){
            return false;
        }
        if (str_starts_with($url, '/')) {
            $url = $this->hostScheme.'://'.$this->hostDomain.$url;
        }

        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    public function getCrawledUrls(): array
    {
        return $this->crawledUrls;
    }

    public function addToCrawledUrls(string $crawledUrl): void
    {
        $this->crawledUrls[$crawledUrl] = true;
    }

    private function hasBeenCrawled(string $url): bool {
        return $this->crawledUrls[$url] ?? false;
    }

    private function buildUrl(string $url) : string{
        if (str_starts_with($url, '/')) {
            $url = $this->hostScheme.'://'.$this->hostDomain.$url;
        }
        return $url;
    }

    private function getHost(string $url): ?string
    {
        $host = parse_url($url, PHP_URL_HOST);
        $domains = explode('.', $host);
        if (count($domains) > 2){
            return implode(".", array_slice($domains, -2));
        }
        return $host;
    }



    private function getScheme(string $url): ?string
    {
        return parse_url($url, PHP_URL_SCHEME);

    }

    protected function setHostDomain(string $domain): void
    {
        if (empty($this->hostDomain)) {
            $this->hostDomain = $domain;
        }
    }

    protected function getHostDomain(): string
    {
        return $this->hostDomain;
    }

    protected function setHostScheme(string $scheme): void
    {
        if (empty($this->hostScheme)) {
            $this->hostScheme = $scheme;
        }
    }

    protected function getHostScheme(): string
    {
        return $this->hostScheme;
    }
}
