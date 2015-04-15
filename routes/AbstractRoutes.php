<?php

namespace Api\Routes;

/**
 * Abstract Route class
 *
 */
abstract class AbstractRoutes
{
    /**
     * Local reference to slim app
     *
     * @param object $app
     */
    protected $app;

    /**
     * Each class should expose a list of available routes
     *
     * @param array $routes list of available routes
     */
    protected $routes = array();

    /**
     * Constructor
     * Create a local reference to the Slim app
     *
     * @return void
     */
    public function __construct ()
    {
        $this->app = \Slim\Slim::getInstance();
    }

    /**
     * Expose public access to routes
     *
     * @return array available routes
     */
    public function getRoutes ()
    {
        return $this->routes;
    }

    /**
     * Output data
     *
     * @param mixed $data Data to output
     * @param int $status The HTTP status (if 200 this response will be cached)
     *
     * @return void
     */
    public function respond ($data = '', $status = 200)
    {
        $this->app->response->setStatus($status);
        $this->app->response->headers->set('Content-Type', 'application/json');
        $this->app->response->setBody(json_encode($data));
    }
}