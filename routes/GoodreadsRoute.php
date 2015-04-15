<?php

namespace Api\Routes;

use Api\Services\GoodreadsService;

/**
 * Goodreads route
 */
class GoodreadsRoute extends AbstractRoutes
{
    /**
     * Define available routes
     *
     * @param array $routes available routes
     */
    protected $routes = array(
        array(
            'name' => 'Goodreads latest books read',
            'url' => '/goodreads/books',
            'methods' => 'GET',
            'params' => 'limit',
            'classMethod' => 'getBooks'
        ),
    );

    /**
     * Public route
     * Request data from service and respond
     */
    public function getBooks ()
    {
        $goodreadsService = new GoodreadsService;
        $limit = $this->app->request->params('limit', 1);
        $data = $goodreadsService->getBooks($limit);
        $this->respond($data);
    }
}