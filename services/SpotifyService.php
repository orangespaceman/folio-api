<?php

namespace Api\Services;

/**
 * Spotify Service class
 *
 */
class SpotifyService extends AbstractService
{
    /**
     * Local copy of relevant configuration settings
     *
     * @param array $config
     */
    private $config;

    /**
     * Instance of the Spotify API wrapper
     *
     * @param object $spotifyApi
     */
    private $spotifyApi;

    /**
     * Prepare service
     * Retrieve config data and configure API library
     *
     * @return void
     */
    public function configureService ()
    {
        $this->config = $this->app->config('spotify');

        // connect to Spotify
        $session = new \SpotifyWebAPI\Session($this->config['client_id'], $this->config['client_secret'], '/');
        $this->spotifyApi = new \SpotifyWebAPI\SpotifyWebAPI();
        $session->requestCredentialsToken();
        $accessToken = $session->getAccessToken();
        $this->spotifyApi->setAccessToken($accessToken);
    }

    /**
     * Perform service request
     *
     * @param int $limit Limit request to required number of responses
     *
     * @return mixed Parsed response
     */
    public function getPlaylists ($limit)
    {
        $data = $this->spotifyApi->getUserPlaylists($this->config['user'], array(
            'limit' => $limit
        ));

        return $this->parsePlaylists($data);
    }

    /**
     * Perform service request
     *
     * @param int $limit Limit request to required number of responses
     *
     * @return mixed Parsed response
     */
    public function getPlaylistTracks ($id)
    {
        $data = $this->spotifyApi->getUserPlaylistTracks($this->config['user'], $id);
        return $this->parsePlaylistTracks($data);
    }

    /**
     * Parse service response
     *
     * @param mixed $data Response received from service
     *
     * @return mixed Parsed response
     */
    protected function parsePlaylists ($data)
    {
        $return = array();

        foreach($data->items as $playlist) {

            $return[] = array(
                'id' => $playlist->id,
                'title' => $playlist->name,
                'url' => $playlist->external_urls->spotify,
                'image' => $playlist->images[count($playlist->images) -1]->url,
                'tracks' => $playlist->tracks->total,
            );
        }

        return $return;
    }


    /**
     * Parse service response
     *
     * @param mixed $data Response received from service
     *
     * @return mixed Parsed response
     */
    protected function parsePlaylistTracks ($data)
    {
        $return = array();

        foreach($data->items as $track) {

            $artists = array();
            foreach($track->track->artists as $artist) {
                $artists[] = $artist->name;
            }

            $return[] = array(
                'url' => $track->track->external_urls->spotify,
                'title' => $track->track->name,
                'artist' => join($artists, ' / '),
                'album' => $track->track->album->name,
                'duration' => $track->track->duration_ms,
                'date' => $track->added_at
            );
        }

        return $return;
    }
}