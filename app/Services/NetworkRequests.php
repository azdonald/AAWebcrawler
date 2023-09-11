<?php
declare(strict_types=1);
namespace App\Services;


use Illuminate\Support\Facades\Http;


interface NetworkRequests {
    public function get(string $url);
}
