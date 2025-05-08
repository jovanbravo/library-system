<?php

return [
    'url' => env('OPEN_LIBRARY_URL', 'https://covers.openlibrary.org/b/isbn/'),
    'rate_limit_key' => env('RATE_LIMIT_KEY', 'open-library-api'),
    'rate_limit_attempts' => env('RATE_LIMIT_ATTEMPTS', 10),
    'valid_sizes' => ['L', 'M', 'S'],
    'http_timeout' => 10,
    'http_retry' => 3,
    'http_retry_sleep' => 100,
];
