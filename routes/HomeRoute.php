<?php

namespace Api\Routes;

/**
 * Home route
 */
class HomeRoute extends AbstractRoutes
{
    /**
     * Define available routes
     *
     * @param array $routes available routes
     */
    protected $routes = array(
        array(
            'name' => 'Home route (lists all available routes)',
            'url' => '/',
            'methods' => 'GET',
            'classMethod' => 'getHomeRoute'
        ),
    );

    /**
     * Return list of available routes
     * (stored in each route, parsed when creating routes in Router class)
     */
    public function getHomeRoute ()
    {
        $this->respond($this->app->routes);
    }
}