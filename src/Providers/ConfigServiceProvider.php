<?php

declare(strict_types=1);

namespace FondBot\Frameworks\Lumen\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class ConfigServiceProvider
 *
 * @property \Laravel\Lumen\Application $app
 *
 * @package FondBot\Frameworks\Lumen\Providers
 */
class ConfigServiceProvider extends ServiceProvider
{
    protected static $filename = 'fondbot.php';

    public function register()
    {
        $appConfigPath = $this->app->basePath('config/fondbot.php');

        if (file_exists($appConfigPath)) {
            $this->app->make('config')->set('fondbot', require $appConfigPath);
        } elseif (file_exists($path = $this->app->basePath('vendor/fondbot/fondbot/config/fondbot.php'))) {
            $this->app->make('config')->set('fondbot', require $path);
        }
    }
}