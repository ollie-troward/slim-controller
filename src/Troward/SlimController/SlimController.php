<?php

namespace Troward\SlimController;

use Slim\Slim;

/**
 * Class SlimController
 * @package Troward\SlimController
 */
class SlimController
{
    /**
     * @var Slim
     */
    protected $app;

    /**
     * @var array
     */
    private $config;

    /**
     * @var array
     */
    private $routes;

    /**
     * @var array
     */
    private $routeMethods = ['GET', 'POST', 'PUT', 'DELETE', 'RESOURCE'];

    /**
     * @param Slim $app
     * @param array $config
     */
    public function __construct(Slim $app, $config = [])
    {
        $this->app = $app;
        $this->config = $config;
    }

    /**
     * @param string $key
     * @return array|string
     * @throws \InvalidArgumentException
     */
    public function getConfig($key = null)
    {
        if (is_null($key)) {
            return $this->config;
        }

        if (!isset($this->config[$key])) {
            throw new \InvalidArgumentException('Invalid configuration option: ' . $key);
        }

        return $this->config[$key];
    }

    /**
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * @param array $routes
     * @return void
     * @throws \InvalidArgumentException
     */
    public function routes($routes = [])
    {
        foreach ($routes as $method => $route) {
            if (!in_array($method, $this->routeMethods)) {
                throw new \InvalidArgumentException('Invalid route method: ' . $method);
            }

            foreach ($route as $uri => $target) {
                $targetParts = explode('@', $target);

                if (count($targetParts) != 2) {
                    throw new \InvalidArgumentException('Invalid controller structure: ' . $target);
                }

                $controller = $targetParts[0];
                $function = $targetParts[1];

                if (isset($this->config['namespace'])) {
                    $controller = $this->config['namespace'] . $controller;
                }

                $callBack = [new $controller($this->app), $function];

                if (!is_callable($callBack)) {
                    throw new \InvalidArgumentException($controller . '::' . $function . ' is not callable');
                }

                $this->app->$method($uri, $callBack);
            }
        }
        $this->routes = $routes;
    }
}
