<?php

namespace App\Repositories;

use App\Contracts\RepositoryInterface;
use App\Models\Book;
use App\Traits\RepositoryMethodsCache;

final class BookRepository implements RepositoryInterface
{
    use RepositoryMethodsCache;

    protected const MODEL = Book::class;
}
