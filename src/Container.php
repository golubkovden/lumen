<?php

declare(strict_types=1);

namespace FondBot\Frameworks\Lumen;

use FondBot\Contracts\Container\Container as FondBotContract;
use Illuminate\Container\Container as BaseContainer;
use Illuminate\Contracts\Container\Container as LumenContract;

class Container implements FondBotContract
{
    private static $instance;
    private $container;

    public function __construct(LumenContract $container)
    {
        $this->container = $container;
    }

    /**
     * Get instance of the container.
     *
     * @return FondBotContract
     */
    public static function instance(): FondBotContract
    {
        if (static::$instance === null) {
            return new static(BaseContainer::getInstance());
        }

        return static::$instance;
    }

    /**
     * Register a binding with the container.
     *
     * @param string|array $abstract
     * @param \Closure|string|null $concrete
     */
    public function bind($abstract, $concrete = null): void
    {
        $this->container->bind($abstract, $concrete);
    }

    /**
     * Register a shared binding in the container.
     *
     * @param string|array $abstract
     * @param \Closure|string|null $concrete
     */
    public function singleton($abstract, $concrete = null): void
    {
        $this->container->singleton($abstract, $concrete);
    }

    /**
     * Resolve the given type from the container.
     *
     * @param string $abstract
     *
     * @return mixed
     */
    public function make(string $abstract)
    {
        return $this->container->make($abstract);
    }
}