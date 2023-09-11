<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class HttpRequests implements NetworkRequests
{
    public function get(string $url): \Illuminate\Http\Client\Response
    {
        return Http::get($url);
    }
}
