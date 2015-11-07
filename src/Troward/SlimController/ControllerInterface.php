<?php

namespace Troward\SlimController;

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Slim;

interface ControllerInterface
{
    /**
     * Set the application instance.
     *
     * @param Slim $app
     * @return void
     */
    public function setApp(Slim $app);

    /**
     * Retrieve the current application instance.
     *
     * @return Slim
     */
    public function app();

    /**
     * Retrieve the current application request.
     *
     * @return Request
     */
    public function request();

    /**
     * Retrieve the current application response.
     *
     * @return Response
     */
    public function response();
}