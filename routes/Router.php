<?php

namespace Api\Routes;

/**
 * Router
 */
class Router {

    /**
     * Local reference to slim app
     *
     * @param object $app
     */
    protected $app;

    /**
     * Consolidated list of all available routes
     *
     * @param array $routes list of routes
     */
    protected $routes;

    /**
     * Constructor
     * Create the Router, using the supplied list of routes
     *
     * @param array $routeClasses Array of routes to instantiate
     *
     * @return void
     */
    public function __construct ($routeClasses)
    {
        // create local shortcut to Slim application
        $this->app = \Slim\Slim::getInstance();

        // create all routes
        $this->parseRoutes($routeClasses);
        $this->createErrorRoutes();

        // make list of routes publicly accessible to other classes
        $this->app->routes = $this->routes;
    }

    /**
     * Parse and generate routes
     *
     * @param array $routeClasses Array of routes to instantiate
     *
     * @return void
     */
    private function parseRoutes ($routeClasses)
    {
        foreach($routeClasses as $routeClass) {

            // some route classes have multiple routes
            foreach($routeClass->getRoutes() as $route) {

                // create route
                $this->app
                    ->map($route['url'], array($routeClass, $route['classMethod']))
                    ->via($route['methods']);

                // store route for later use
                $this->routes[] = $route;
            }
        }
    }

    /**
     * Create catch-all route for any unmatched routes
     *
     * @return void
     */
    private function createErrorRoutes ()
    {
        $this->app->get('/:method', '\Api\Routes\ErrorRoute:getRouteNotFound')
            ->conditions(array('method' => '.+'));
    }
}