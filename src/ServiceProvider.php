<?php

declare(strict_types=1);

namespace FondBot\Frameworks\Lumen;

use FondBot\Channels\ChannelManager;
use FondBot\Conversation\FallbackIntent;
use FondBot\Conversation\IntentManager;
use FondBot\Drivers\DriverManager;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

/**
 * Class ServiceProvider
 *
 * @property \Laravel\Lumen\Application $app
 *
 * @package FondBot\Frameworks\Lumen
 */
class ServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        $this->app->bind(\FondBot\Contracts\Cache::class, Cache::class);
        $this->app->bind(\FondBot\Contracts\Container::class, Container::class);
        $this->app->bind(\FondBot\Contracts\Filesystem::class, Filesystem::class);

        $this->app->singleton(DriverManager::class, DriverManager::class);
        $this->app->singleton(ChannelManager::class, ChannelManager::class);
        $this->app->singleton(IntentManager::class, IntentManager::class);
    }

    public function boot()
    {
        $this->loadConfig();
        $this->loadRoutes();
        $this->loadDrivers();
        $this->loadChannels();
        $this->loadIntents();
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
        ], function ($app) {
            $app->addRoute(
                ['GET', 'HEAD', 'POST', 'PUT', 'PATCH', 'DELETE'],
                '{channel}',
                ['as' => 'fondbot.webhook', 'uses' => 'WebhookController@handle']
            );
        });
    }

    protected function loadDrivers()
    {
        $manager = $this->app->make(DriverManager::class);
        $drivers = $this->app['config']->get('fondbot.drivers', []);

        foreach ($drivers as $alias => $class) {
            $manager->add($alias, app($class));
        }
    }

    protected function loadChannels()
    {
        $manager = $this->app->make(ChannelManager::class);
        $channels = $this->app['config']->get('fondbot.channels', []);

        foreach ($channels as $name => $parameters) {
            $manager->add($name, $parameters);
        }
    }

    protected function loadIntents()
    {
        $manager = $this->app->make(IntentManager::class);
        $intents = $this->app->app['config']->get('fondbot.intents', []);

        foreach ($intents as $intent) {
            $manager->add(app($intent));
        }

        $manager->setFallbackIntent(app(
            $this->app['config']->get('fondbot.fallback_intent', FallbackIntent::class)
        ));
    }
}