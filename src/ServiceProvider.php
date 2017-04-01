<?php

declare(strict_types=1);

namespace FondBot\Frameworks\Lumen;

use FondBot\Channels\ChannelManager;
use FondBot\Contracts\Container\Container as ContainerContract;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        $this->app->bind(ContainerContract::class, Container::class);
        $this->app->singleton(ChannelManager::class, ChannelManager::class);
    }

    public function boot()
    {
        $this->loadConfig();
        $this->loadRoutes();
        $this->loadChannels();
    }

    protected function loadConfig()
    {
        $this->app->configure('fondbot');

        $path = $this->app->basePath('vendor/fondbot/fondbot/config/fondbot.php');
        $this->mergeConfigFrom($path, 'fondbot');
    }

    protected function loadRoutes()
    {
        $this->app->group([
            'prefix' => 'fondbot',
            'namespace' => 'FondBot\Frameworks\Lumen\Http\Controllers',
//            'middleware' => ['bindings'],
        ], function ($app) {
            $app->addRoute(
                ['GET', 'HEAD', 'POST', 'PUT', 'PATCH', 'DELETE'],
                '{channel}',
                ['as' => 'fondbot.webhook', 'uses' => 'WebhookController@handle']
            );
        });
    }

    protected function loadChannels()
    {
        $manager = $this->app->make(ChannelManager::class);
        $channels = $this->app->make('config')->get("fondbot.channels", []);

        foreach ($channels as $name => $parameters) {
            $manager->add($name, $parameters);
        }
    }
}