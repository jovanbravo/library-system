<?php

namespace App\Http\Controllers;

use App\Contracts\RequestInterface;
use App\Contracts\ServiceInterface;

abstract class Controller
{
    public function __construct(
        protected ServiceInterface $service,
        protected RequestInterface $request
    ) {}
}
