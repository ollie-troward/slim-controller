<?php

namespace spec\Troward\SlimController;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Slim\Slim;

/**
 * Class SlimControllerSpec
 * @package spec\Troward\SlimController
 */
class SlimControllerSpec extends ObjectBehavior
{
    /**
     * @var Slim
     */
    protected $app;

    /**
     * It can be initialized.
     */
    function it_is_initializable()
    {
        $this->shouldHaveType('Troward\SlimController\SlimController');
    }

    /**
     * Construct with a Slim instance.
     */
    function let()
    {
        $this->app = new Slim;
        $this->beConstructedWith($this->app);
    }

    /**
     * It returns a controller namespace.
     */
    function it_returns_an_application_namespace()
    {
        $config = ['namespace' => 'MyApp\\Controllers\\'];

        $this->beConstructedWith($this->app, $config);

        $this->getConfig('namespace')->shouldReturn($config['namespace']);
    }

    /**
     * It returns all configuration options.
     */
    function it_returns_all_config_options()
    {
        $config = ['namespace' => 'MyApp\\Controllers\\'];

        $this->beConstructedWith($this->app, $config);

        $this->getConfig()->shouldReturn($config);
    }

    /**
     * It disallows invalid configuration options.
     */
    function it_disallows_invalid_config_options()
    {
        $config = ['namespace' => 'MyApp\\Controllers\\'];

        $this->beConstructedWith($this->app, $config);

        $expectedKey = 'food';

        $this->shouldThrow(new \InvalidArgumentException('Invalid configuration option: ' . $expectedKey))
            ->duringGetConfig($expectedKey);
    }

    /**
     * It accepts an array of valid routes.
     */
    function it_accepts_an_array_of_valid_routes()
    {
        $config = ['namespace' => 'spec\\Troward\\Controllers\\'];

        $this->beConstructedWith($this->app, $config);

        $routes = [
            'GET' => [
                '/' => 'UserController@index'
            ],
            'POST' => [
                '/' => 'UserController@create'
            ],
            'PUT' => [
                '/' => 'UserController@update'
            ],
            'DELETE' => [
                '/' => 'UserController@delete'
            ],
        ];

        $this->routes($routes);

        $this->getRoutes()->shouldReturn($routes);
    }

    /**
     * It disallows invalid route methods.
     */
    function it_disallows_an_invalid_method()
    {
        $config = ['namespace' => 'spec\\Troward\\Controllers\\'];

        $this->beConstructedWith($this->app, $config);

        $routes = [
            'EAT' => [
                '/' => 'UserController@index'
            ],
        ];

        $expectedMethod = key($routes);

        $this->shouldThrow(new \InvalidArgumentException('Invalid route method: ' . $expectedMethod))
            ->duringRoutes($routes);
    }

    /**
     * It disallows an invalid controller and method structure.
     */
    function it_disallows_an_invalid_controller_structure()
    {
        $config = ['namespace' => 'spec\\Troward\\Controllers\\'];

        $this->beConstructedWith($this->app, $config);

        $routes = [
            'GET' => [
                '/' => 'UserCo@ntroller@index@:ASDKO@KR$OKW'
            ],
        ];

        $expectedRoute = $routes['GET']['/'];

        $this->shouldThrow(new \InvalidArgumentException('Invalid controller structure: ' . $expectedRoute))
            ->duringRoutes($routes);
    }

    /**
     * It disallows invalid controller declarations.
     */
    function it_disallows_invalid_controllers()
    {
        $config = ['namespace' => 'spec\\Troward\\Controllers\\'];

        $this->beConstructedWith($this->app, $config);

        $routes = [
            'GET' => [
                '/' => 'SuperController@index'
            ],
        ];

        $expectedController = $config['namespace'] . explode('@', $routes['GET']['/'])[0];

        $this->shouldThrow(new \InvalidArgumentException('Invalid controller specified: ' . $expectedController))
            ->duringRoutes($routes);
    }

    /**
     * It disallows functions that cannot be called.
     */
    function it_disallows_functions_that_cannot_be_called()
    {
        $config = ['namespace' => 'spec\\Troward\\Controllers\\'];

        $this->beConstructedWith($this->app, $config);

        $routes = [
            'GET' => [
                '/' => 'UserController@superFunction'
            ],
        ];

        $expectedRoute = $config['namespace'] . str_replace('@', '::', $routes['GET']['/']);

        $this->shouldThrow(new \InvalidArgumentException($expectedRoute . ' is not callable'))
            ->duringRoutes($routes);
    }
}
