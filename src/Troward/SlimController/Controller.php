<?php

namespace Troward\SlimController;

use Slim\Slim;

/**
 * Class Controller
 * @package Troward\SlimController
 */
class Controller
{
    /**
     * The Slim instance.
     *
     * @var Slim
     */
    protected $app;

    /**
     * Set the application instance.
     *
     * @param Slim $app
     * @return void
     */
    final public function setApp(Slim $app)
    {
        $this->app = $app;
    }

    /**
     * Retrieve the current application instance.
     *
     * @return Slim
     */
    public function app()
    {
        return $this->app;
    }

    /**
     * Retrieve the current application request.
     *
     * @return \Slim\Http\Request
     */
    public function request()
    {
        return $this->app->request();
    }

    /**
     * Retrieve the current application response.
     *
     * @return \Slim\Http\Response
     */
    public function response()
    {
        return $this->app->response();
    }
}
