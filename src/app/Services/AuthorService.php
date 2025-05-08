<?php

namespace App\Services;

use App\Contracts\ServiceInterface;
use App\Http\Resources\AuthorDetailResource;
use App\Http\Resources\AuthorsResource;
use App\Services\Core\BaseService;
use App\Traits\ServiceBaseMethods;

final class AuthorService extends BaseService implements ServiceInterface
{
    use ServiceBaseMethods;

    protected const INDEX_RESOURCE = AuthorsResource::class;
    protected const DETAIL_RESOURCE = AuthorDetailResource::class;
}
