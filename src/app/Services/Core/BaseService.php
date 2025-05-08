<?php

namespace App\Services\Core;

use App\Contracts\RepositoryInterface;

abstract class BaseService
{
    public function __construct(
        protected RepositoryInterface $repository
    ) {}
}
