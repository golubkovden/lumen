<?php

declare(strict_types=1);

namespace FondBot\Frameworks\Lumen;

use FondBot\Channels\ChannelManager;
use FondBot\Channels\DriverManager;
use FondBot\Channels\Facebook\FacebookDriver;
use FondBot\Channels\Telegram\TelegramDriver;
use FondBot\Channels\VkCommunity\VkCommunityDriver;
use FondBot\Contracts\Container\Container as ContainerContract;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    protected $drivers = [
        'facebook' => FacebookDriver::class,
        'telegram' => TelegramDriver::class,
        'vk-communities' => VkCommunityDriver::class,
    ];

    public function register()
    {
        $this->app->bind(ContainerContract::class, Container::class);
        $this->app->singleton(ChannelManager::class, ChannelManager::class);
        $this->app->singleton(DriverManager::class, DriverManager::class);
    }

    public function boot()
    {
        $this->loadConfig();
        $this->loadRoutes();
        $this->loadChannels();
        $this->loadDrivers();
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

    protected function loadDrivers()
    {
        $manager = $this->app->make(DriverManager::class);

        foreach ($this->drivers as $alias => $class) {
            $manager->add($alias, $this->app->make($class));
        }
    }
}