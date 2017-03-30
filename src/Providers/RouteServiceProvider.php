<?php

declare(strict_types=1);

namespace FondBot\Frameworks\Lumen\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application;

/**
 * Class RouteServiceProvider
 *
 * @property Application $app
 *
 * @package FondBot\Frameworks\Lumen\Providers
 */
class RouteServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->group([
            'prefix' => 'fondbot',
            'namespace' => 'FondBot\Frameworks\Lumen\Http\Controllers',
            'middleware' => ['bindings'],
        ], function (Application $app) {
            $app->addRoute(
                ['GET', 'HEAD', 'POST', 'PUT', 'PATCH', 'DELETE'],
                '{channel}',
                ['as' => 'fondbot.webhook', 'uses' => 'WebhookController@handle']
            );
        });
    }
}