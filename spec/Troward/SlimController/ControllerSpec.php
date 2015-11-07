<?php

namespace spec\Troward\SlimController;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Slim\Environment;
use Slim\Slim;

/**
 * Class ControllerSpec
 * @package spec\Troward\SlimController
 */
class ControllerSpec extends ObjectBehavior
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
        $this->shouldHaveType('Troward\SlimController\Controller');
    }

    /**
     * Set the application instance.
     */
    function let()
    {
        $this->app = new Slim();
        $this->app->environment = Environment::mock();
        $this->setApp($this->app);
    }

    /**
     * It returns the current application request.
     */
    function it_returns_the_current_request()
    {
        $this->request()->shouldReturnAnInstanceOf('Slim\Http\Request');
    }

    /**
     * It returns the current application response.
     */
    function it_returns_the_current_response()
    {
        $this->response()->shouldReturnAnInstanceOf('Slim\Http\Response');
    }
}
