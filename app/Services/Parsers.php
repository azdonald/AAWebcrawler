<?php
declare(strict_types=1);

namespace App\Services;

interface Parsers {
    function parseRequest(string $body, string $type);
}


