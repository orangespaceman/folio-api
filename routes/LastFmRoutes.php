<?php

namespace Api\Routes;

use Api\Services\LastFmService;

/**
 * Last.fm routes
 */
class LastFmRoutes extends AbstractRoutes
{
    /**
     * Define available routes
     *
     * @param array $routes available routes
     */
    protected $routes = array(
        array(
            'name' => 'Last.fm latest tracks',
            'url' => '/lastfm/latest-tracks',
            'methods' => 'GET',
            'params' => 'limit',
            'classMethod' => 'getLatestTracks'
        ),
        array(
            'name' => 'Last.fm top artists',
            'url' => '/lastfm/top-artists',
            'methods' => 'GET',
            'params' => 'limit',
            'classMethod' => 'getTopArtists'
        )
    );

    /**
     * Public route
     * Request data from service and respond
     */
    public function getLatestTracks ()
    {
        $lastFmService = new LastFmService;
        $limit = $this->app->request->params('limit', 1);
        $data = $lastFmService->getLatestTracks($limit);
        $this->respond($data);
    }

    /**
     * Public route
     * Request data from service and respond
     */
    public function getTopArtists ()
    {
        $lastFmService = new LastFmService;
        $limit = $this->app->request->params('limit', 20);
        $data = $lastFmService->getTopArtists($limit);
        $this->respond($data);
    }
}