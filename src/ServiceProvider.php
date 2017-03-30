<?php

declare(strict_types=1);

namespace FondBot\Frameworks\Lumen;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        $this->app->register(Providers\RouteServiceProvider::class);
    }
}