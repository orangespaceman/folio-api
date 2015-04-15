<?php

namespace Api\Routes;

use Api\Services\SpotifyService;

/**
 * Spotify routes
 */
class SpotifyRoutes extends AbstractRoutes
{
    /**
     * Define available routes
     *
     * @param array $routes available routes
     */
    protected $routes = array(
        array(
            'name' => 'Spotify playlists',
            'url' => '/spotify/playlists',
            'methods' => 'GET',
            'params' => 'limit',
            'classMethod' => 'getPlaylists'
        ),
        array(
            'name' => 'Spotify playlist tracklisting',
            'url' => '/spotify/playlists/:id',
            'methods' => 'GET',
            'params' => 'id',
            'classMethod' => 'getPlaylistTracks'
        )
    );

    /**
     * Public route
     * Request data from service and respond
     */
    public function getPlaylists ()
    {
        $spotifyService = new SpotifyService;
        $limit = $this->app->request->params('limit', 50);
        $data = $spotifyService->getPlaylists($limit);
        $this->respond($data);
    }

    /**
     * Public route
     * Request data from service and respond
     */
    public function getPlaylistTracks ($id)
    {
        $spotifyService = new SpotifyService;
        $data = $spotifyService->getPlaylistTracks($id);
        $this->respond($data);
    }
}