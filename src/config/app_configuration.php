<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Requests\AuthorRequest;
use App\Http\Requests\BookRequest;
use App\Repositories\AuthorRepository;
use App\Repositories\BookRepository;
use App\Services\AuthorService;
use App\Services\BookService;

return [
    AuthorController::class => [
        'service' => AuthorService::class,
        'repository' => AuthorRepository::class,
        'request' => AuthorRequest::class,
    ],

    BookController::class => [
        'service' => BookService::class,
        'repository' => BookRepository::class,
        'request' => BookRequest::class,
    ]
];
