<?php

namespace App\Providers;

use App\Contracts\RepositoryInterface;
use App\Contracts\RequestInterface;
use App\Contracts\ServiceInterface;
use App\Http\Requests\BasicRequest;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        foreach (config('app_configuration') as $key => $value) {
            $this->app->when($key)
                ->needs(ServiceInterface::class)
                ->give($value['service']);

            $this->app->when($key)
                ->needs(RequestInterface::class)
                ->give($value['request'] ?? BasicRequest::class);

            $this->app->when($value['service'])
                ->needs(RepositoryInterface::class)
                ->give($value['repository']);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
