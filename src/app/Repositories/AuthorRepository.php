<?php

namespace App\Repositories;

use App\Contracts\RepositoryInterface;
use App\Models\Author;
use App\Traits\RepositoryBaseMethods;
use App\Traits\RepositoryMethodsCache;

final class AuthorRepository implements RepositoryInterface
{
    use RepositoryBaseMethods;

    protected const MODEL = Author::class;
}
