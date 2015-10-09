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
     * The Slim instance.
     *
     * @var Slim
     */
    protected $app;

    /**
     * Configuration options.
     *
     * @var array
     */
    private $config;

    /**
     * Application routes.
     *
     * @var array
     */
    private $routes;

    /**
     * Route methods.
     *
     * @var array
     */
    private $routeMethods = ['GET', 'POST', 'PUT', 'DELETE', 'RESOURCE'];

    /**
     * SlimController constructor.
     *
     * @param Slim  $app
     * @param array $config
     */
    public function __construct(Slim $app, $config = [])
    {
        $this->app    = $app;
        $this->config = $config;
    }

    /**
     * Retrieve the configuration.
     *
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
     * Retrieve the routes.
     *
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * Set the routes.
     *
     * @param array $routes
     * @return void
     * @throws \InvalidArgumentException
     */
    public function routes($routes = [])
    {
        foreach ($routes as $method => $route) {
            if (!$this->isValidRoute($method)) {
                throw new \InvalidArgumentException('Invalid route method: ' . $method);
            }

            foreach ($route as $uri => $target) {
                $targetParts = explode('@', $target);

                if (!$this->isValidControllerStructure($targetParts)) {
                    throw new \InvalidArgumentException('Invalid controller structure: ' . $target);
                }

                $controller = $targetParts[0];
                $function   = $targetParts[1];

                if (isset($this->config['namespace'])) {
                    $controller = $this->config['namespace'] . $controller;
                }

                if (!class_exists($controller)) {
                    throw new \InvalidArgumentException('Invalid controller specified: ' . $controller);
                }

                $baseController = new $controller;
                $baseController->setApp($this->app);

                $callBack = [$baseController, $function];

                if (!is_callable($callBack)) {
                    throw new \InvalidArgumentException($controller . '::' . $function . ' is not callable');
                }

                $this->app->$method($uri, $callBack);
            }
        }
        $this->routes = $routes;
    }

    /**
     * Validate the route.
     *
     * @param $method
     * @return bool
     */
    private function isValidRoute($method)
    {
        return in_array($method, $this->routeMethods);
    }

    /**
     * Validate the controller and function structure.
     *
     * @param array $targetParts
     * @return bool
     */
    private function isValidControllerStructure(array $targetParts)
    {
        return count($targetParts) == 2;
    }
}
