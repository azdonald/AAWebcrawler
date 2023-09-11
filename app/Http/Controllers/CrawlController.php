<?php

namespace App\Http\Controllers;

use App\Services\Crawler;
use App\Services\HtmlParser;
use App\Services\HttpRequests;
use Illuminate\Http\Request;
use function Symfony\Component\String\u;

class CrawlController extends Controller
{

    public function index()
    {
        return view('welcome');
    }

    public function crawl(Request $request)
    {
        $request->validate(['url' => 'required']);
        $url = $request->input('url');
        $networkReq = new HttpRequests();
        $parser = new HtmlParser();
        $crawler = new Crawler($networkReq, $parser);
        $resp = $crawler->crawl($url);
        $averages = $this->processResult($resp);

        return view('welcome', ['pages' => $resp, 'data' => $averages]);
    }

    private function processResult(array $result): array
    {
        $response = array();

        $response['Number of Pages '] = count($result);
        $unique_images = array();
        $title_size = array();
        $page_load_size = array();
        $unique_internalLinks = array();
        $unique_externalLinks = array();
        foreach ($result as $r) {
            $unique_images[] = array_unique($r->getImages());
            $unique_externalLinks =  array_merge($unique_externalLinks, $r->getExternalLinks());
            $unique_internalLinks = array_merge($unique_internalLinks,$r->getInternalLinks());
            $title_size[] = strlen($r->getTitle());
            $page_load_size[] = $r->getLoadTime();
        }

        $response['Number of Unique Images'] = count($unique_images);
        $response['Average Load Time'] = $this->calculateAverage($page_load_size);
        $response['Average Title Size'] = $this->calculateAverage($title_size);
        $response['Number of Internal Links'] = count(array_unique($unique_internalLinks));
        $response['Number of External Links'] = count(array_unique($unique_externalLinks));
        return $response;
    }

    private function calculateAverage(array $input): float|int
    {
        if (count($input) == 0) {
            return 0;
        }
        return (array_sum($input)/ count($input));
    }
}
