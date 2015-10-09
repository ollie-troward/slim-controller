<?php

namespace Troward\SlimController;

use Slim\Slim;

class Controller
{
    protected $app;

    final public function setApp(Slim $app)
    {
        $this->app = $app;
    }
}
