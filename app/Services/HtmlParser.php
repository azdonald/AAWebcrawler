<?php

namespace App\Services;


use DOMDocument;

class HtmlParser implements Parsers {

    private DOMDocument $domDoc;
    public function __construct()
    {
        $this->domDoc = new DOMDocument();
    }


    /**
     * @param string $body
     * @param string $type
     * @return array
     */
    function parseRequest(string $body, string $type): array
    {
        libxml_use_internal_errors(true);
        $this->domDoc->loadHTML($body);
        $result = array();
        switch ($type){
            case "url":
                $elements = $this->domDoc->getElementsByTagName('a');
                foreach ($elements as $link) {
                    if ($link->getAttribute('href') != "#") {
                        $result[] = $link->getAttribute('href');
                    }
                }
                return $result;
            case "image":
                $elements = $this->domDoc->getElementsByTagName('img');
                foreach ($elements as $image) {
                    $result[] = $image->getAttribute('src');
                }
                return $result;
            case "title":
                $titles = $this->domDoc->getElementsByTagName('title');
                foreach ($titles as $title) {
                    $result[] = $title->nodeValue;
                }
                return $result;

        }
        return $result;
    }
}
