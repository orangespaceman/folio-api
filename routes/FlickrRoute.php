<?php

namespace Api\Routes;

use Api\Services\FlickrService;

/**
 * Flickr route
 */
class FlickrRoute extends AbstractRoutes
{
    /**
     * Define available routes
     *
     * @param array $routes available routes
     */
    protected $routes = array(
        array(
            'name' => 'Flickr latest photos',
            'url' => '/flickr/photos',
            'methods' => 'GET',
            'params' => 'limit',
            'classMethod' => 'getPhotos'
        ),
    );

    /**
     * Public route
     * Request data from service and respond
     */
    public function getPhotos ()
    {
        $flickrService = new FlickrService;
        $limit = $this->app->request->params('limit', 10);
        $data = $flickrService->getPhotos($limit);
        $this->respond($data);
    }
}