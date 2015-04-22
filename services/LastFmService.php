<?php

namespace Api\Services;

/**
 * Flickr Service class
 *
 */
class LastFmService extends AbstractService
{
    /**
     * Any params that will be appended to all queries
     *
     * @param array $params General request parameters
     */
    protected $params = array('format' => 'json');

    /**
     * Service request base
     * Will be appended to with params, etc
     *
     * @param string $apiBase
     */
    protected $apiBase = 'http://ws.audioscrobbler.com/2.0/?';

    /**
     * Prepare service
     * Retrieve config data and prep params
     *
     * @return void
     */
    public function configureService ()
    {
        $params = $this->app->config('lastfm');
        $this->params = array_merge($this->params, $params);
    }

    /**
     * Perform service request
     *
     * @param int $limit Limit request to required number of responses
     *
     * @return mixed Parsed response
     */
    public function getLatestTracks ($limit)
    {
        $params = array(
            'method' => 'user.getrecenttracks',
            'limit' => $limit
        );
        $data = $this->constructRequest($params);
        return $this->parseLatestTracks($data);
    }

    /**
     * Perform service request
     *
     * @param int $limit Limit request to required number of responses
     *
     * @return mixed Parsed response
     */
    public function getTopArtists ($limit)
    {
        $params = array(
            'method' => 'user.gettopartists',
            'limit' => $limit
        );
        $data = $this->constructRequest($params);
        return $this->parseTopArtists($data);
    }

    /**
     * Parse service response
     *
     * @param mixed $data Response received from service
     *
     * @return mixed Parsed response
     */
    private function parseLatestTracks ($data)
    {
        $return = array();

        $latestTracks = json_decode($data);

        $tracks = $latestTracks->recenttracks->track;
        if (!is_array($tracks)) {
            $tracks = array($tracks);
        }

        foreach($tracks as $track) {
            $return[] = array(
                'artist' => $track->artist->{'#text'},
                'title' => $track->name,
                'date' =>$track->date->{'#text'}
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
    private function parseTopArtists ($data)
    {
        $return = array();

        $topArtists = json_decode($data);

        $artists = $topArtists->topartists->artist;
        if (!is_array($artists)) {
            $artists = array($artists);
        }

        $topPlayCount = null;

        foreach($artists as $artist) {

            if (!$topPlayCount) {
                $topPlayCount = $artist->playcount;
            }

            $return[] = array(
                'artist' => $artist->name,
                'playcount' => $artist->playcount,
                'playcount_percentage' => round(($artist->playcount / $topPlayCount) * 100),
                'image' => $artist->image[0]->{'#text'}
            );
        }

        return $return;
    }
}