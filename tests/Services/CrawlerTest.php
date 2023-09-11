<?php

namespace Tests\Unit;


use App\Services\Crawler;
use App\Services\HtmlParser;
use App\Services\MockNetworkRequest;
use Tests\TestCase;

class CrawlerTest extends TestCase
{
    public function testCrawler(): void
    {
        $parser = new HtmlParser();
        $network = new MockNetworkRequest();
        $crawler = new Crawler($network, $parser);
        $result = $crawler->crawl("https://www.example.com/");
        $this->assertIsArray($result);
        $this->assertCount(3, $result);

    }
}
