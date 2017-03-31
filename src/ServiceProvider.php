<?php

declare(strict_types=1);

namespace FondBot\Frameworks\Lumen;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Laravel\Lumen\Application;

/**
 * Class ServiceProvider
 * @property Application $app
 * @package FondBot\Frameworks\Lumen
 */
class ServiceProvider extends BaseServiceProvider
{
    public function register()
    {
//        $this->app->register(Providers\ConfigServiceProvider::class);
        $this->app->register(Providers\RouteServiceProvider::class);
    }
}