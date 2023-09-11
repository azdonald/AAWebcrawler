<?php
declare(strict_types=1);
namespace App\Services;
use \Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;


define("VALID_PAGE", "<!DOCTYPE html>
<html>
<head>
<title>Page Title</title>
</head>
<body>

<h1>This is a Heading</h1>
<p>This is a paragraph.</p>
<a href='https://www.example.com/'>link text</a>
<a href='https://www.example.com/house'>link text</a>
<a href='https://www.example.com/fish'>link text</a>
<a href='https://www.mydomain.com/'>link text</a>
<a href='https://www.mydomain.com/house'>link text</a>
<img src='img_girl.jpg' alt='Girl in a jacket'>
<img src='imgs.jpg' alt='Girl in a jacket'>


</body>
</html>");
class MockNetworkRequest implements NetworkRequests
{

    /**
     * @param string $url
     * @return mixed
     */
    public function get(string $url)
    {
        Http::preventStrayRequests();
       Http::fake([
            'www.example.com/*' => Http::response(VALID_PAGE, 200)
        ]);
       return Http::get($url);

    }
}
