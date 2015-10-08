<?php

namespace spec\Troward\SlimController;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Slim\Slim;

class SlimControllerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Troward\SlimController\SlimController');
    }

    function let()
    {
        $app = new Slim;
        $this->beConstructedWith($app);
    }

    function it_returns_an_application_namespace()
    {
        $app = new Slim;
        $config = ['namespace' => 'MyApp\\Controllers\\'];

        $this->beConstructedWith($app, $config);

        $this->getConfig('namespace')
            ->shouldReturn($config['namespace']);
    }

    function it_accepts_an_array_of_valid_routes()
    {
        $app = new Slim;
        $config = ['namespace' => 'spec\\Troward\\Controllers\\'];

        $this->beConstructedWith($app, $config);

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

        $this->getRoutes()
            ->shouldReturn($routes);
    }

    function it_disallows_an_array_with_an_invalid_method()
    {
        $routes = [
            'RESOURCE' => [
                'user' => 'UserController'
            ],
            'EAT' => [
                '/' => 'UserController@index'
            ],
        ];

        $this->shouldThrow('\InvalidArgumentException')
            ->duringRoutes($routes);
    }
}
