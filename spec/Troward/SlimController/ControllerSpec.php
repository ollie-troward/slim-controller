<?php

namespace spec\Troward\SlimController;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ControllerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Troward\SlimController\Controller');
    }
}
