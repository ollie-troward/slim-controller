<?php

namespace spec\Troward\SlimController;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Slim\Slim;

/**
 * Class ControllerSpec
 * @package spec\Troward\SlimController
 */
class ControllerSpec extends ObjectBehavior
{
    /**
     * It can be initialized.
     */
    function it_is_initializable()
    {
        $this->shouldHaveType('Troward\SlimController\Controller');
    }

    /**
     * It accepts an instance of Slim.
     */
    function it_accepts_an_app_instance()
    {
        $app = new Slim;
        $this->setApp($app);
    }
}
