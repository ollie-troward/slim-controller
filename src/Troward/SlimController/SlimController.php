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
    private $routeMethods = ['GET', 'POST', 'PUT', 'DELETE'];

    /**
     * Initialised controllers.
     *
     * @var array
     */
    private $controllers = [];

    /**
     * SlimController constructor.
     *
     * @param Slim $app
     * @param array $config
     */
    public function __construct(Slim $app, $config = [])
    {
        $this->app = $app;
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
        foreach ($routes as $httpVerb => $route) {
            $this->validateHttpVerb($httpVerb);

            foreach ($route as $uri => $route) {
                $route = $this->splitRoute($route);

                $controller = $this->retrieveController($route['controller']);

                $controller->setApp($this->app);

                $this->execute($controller, $route['method'], $httpVerb, $uri);
            }

            $this->routes = $routes;
        }
    }

    /**
     * Validate the HTTP Verb.
     *
     * @param string $httpVerb
     * @return void
     * @throws \InvalidArgumentException
     */
    private function validateHttpVerb($httpVerb)
    {
        if (!in_array($httpVerb, $this->routeMethods)) {
            throw new \InvalidArgumentException('Invalid route method: ' . $httpVerb);
        }
    }

    /**
     * Split and validate the route structure.
     *
     * @param string $route
     * @return array
     * @throws \InvalidArgumentException
     */
    private function splitRoute($route)
    {
        $routeParts = explode('@', $route);

        if (count($routeParts) !== 2) {
            throw new \InvalidArgumentException('Invalid controller structure: ' . $route);
        }

        return [
            'controller' => $routeParts[0],
            'method' => $routeParts[1],
        ];
    }

    /**
     * Append the namespace if specified.
     * Check the class exists.
     * Check that we haven't already stored the controller from earlier.
     * Check that it extends from the correct Controller.
     * Return a instance of the controller.
     *
     * @param string $controller
     * @return mixed
     * @throws \InvalidArgumentException
     * @throws \BadMethodCallException
     */
    private function retrieveController($controller)
    {
        if (isset($this->config['namespace'])) {
            $controller = $this->config['namespace'] . $controller;
        }

        if (!class_exists($controller)) {
            throw new \InvalidArgumentException('Invalid controller specified: ' . $controller);
        }

        if (array_key_exists($controller, $this->controllers)) {
            return $this->controllers[$controller];
        }

        $this->controllers[$controller] = new $controller;

        if (!is_subclass_of($controller, Controller::class)) {
            throw new \BadMethodCallException(get_class($controller) . ' does not extend: ' . Controller::class);
        }

        return $this->controllers[$controller];
    }

    /**
     * Execute the route.
     *
     * @param ControllerInterface $controller
     * @param string $method
     * @param string $httpVerb
     * @param string $route
     * @return void
     * @throws \InvalidArgumentException
     */
    private function execute(ControllerInterface $controller, $method, $httpVerb, $route)
    {
        $callBack = [$controller, $method];

        if (!is_callable($callBack)) {
            throw new \InvalidArgumentException(get_class($controller) . '::' . $method . ' is not callable');
        }

        $this->app->$httpVerb($route, $callBack);
    }
}
