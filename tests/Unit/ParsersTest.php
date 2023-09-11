<?php

namespace Tests\Unit;

use App\Services\HtmlParser;
use PHPUnit\Framework\TestCase;

define("VALID_PAGE", "<!DOCTYPE html>
<html>
<head>
<title>Page Title</title>
</head>
<body>

<h1>This is a Heading</h1>
<p>This is a paragraph.</p>
<a href='https://www.example.com/'>link text</a>
<img src='img_girl.jpg' alt='Girl in a jacket'>
<img src='imgs.jpg' alt='Girl in a jacket'>


</body>
</html>");

define("PAGE_NO_TITLE", "<!DOCTYPE html>
<html>
<head>
<title></title>
</head>
<body>

<h1>This is a Heading</h1>
<p>This is a paragraph.</p>

</body>
</html>");


class ParsersTest extends TestCase
{
    public function testParseRequestTitle () :void
    {
        $parsers = new HtmlParser();
        $result = $parsers->parseRequest(VALID_PAGE, "title");
        $this->assertIsArray($result);
        $this->assertEquals("Page Title", $result[0]);

        $result = $parsers->parseRequest(PAGE_NO_TITLE, "title");
        $this->assertIsArray($result);
        $this->assertEquals("", $result[0]);
    }


    public function testParseRequestUrls () :void
    {
        $parsers = new HtmlParser();
        $result = $parsers->parseRequest(VALID_PAGE, "url");
        $this->assertIsArray($result);
        $this->assertEquals("https://www.example.com/", $result[0]);


        $result = $parsers->parseRequest(PAGE_NO_TITLE, "url");
        $this->assertIsArray($result);
        $this->assertEquals("", $result[0]);
    }

    public function testParseRequestImages () :void
    {
        $parsers = new HtmlParser();
        $result = $parsers->parseRequest(VALID_PAGE, "image");
        $this->assertIsArray($result);
        $this->assertEquals("img_girl.jpg", $result[0]);
        $this->assertEquals("imgs.jpg", $result[1]);

        $result = $parsers->parseRequest(PAGE_NO_TITLE, "image");
        $this->assertIsArray($result);
        $this->assertEquals("", $result[0]);
    }
}
