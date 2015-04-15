<?php

namespace Api\Services;

/**
 * Flickr Service class
 *
 */
class FlickrService extends AbstractService
{
    /**
     * Any params that will be appended to all queries
     *
     * @param array $params General request parameters
     */
    protected $params = array(
        'format' => 'json',
        'nojsoncallback' => 1
    );

    /**
     * Service request base
     * Will be appended to with params, etc
     *
     * @param string $apiBase
     */
    protected $apiBase = 'https://api.flickr.com/services/feeds/photos_public.gne?';

    /**
     * Prepare service
     * Retrieve config data and prep params
     *
     * @return void
     */
    public function configureService ()
    {
        $params = $this->app->config('flickr');
        $this->params = array_merge($this->params, $params);
    }

    /**
     * Perform service request
     *
     * @param int $limit Limit request to required number of responses
     *
     * @return mixed Parsed response
     */
    public function getPhotos ($limit)
    {
        $data = $this->constructRequest();
        return $this->parsePhotos($data, $limit);
    }

    /**
     * Parse service response
     *
     * @param mixed $data Response received from service
     * @param int $limit Limit request to required number of responses
     *
     * @return mixed Parsed response
     */
    protected function parsePhotos ($data, $limit)
    {
        $return = array();

        // fix flickr invalid JSON response
        $data = str_replace('\\\'', '\'', $data);

        $photos = json_decode($data);

        $counter = 0;

        foreach($photos->items as $photo) {

            if ($counter++ == $limit) {
                break;
            }

            $return[] = array(
                'title' => $photo->title,
                'url' => $photo->link,
                'image' => $photo->media->m,
                'date' => $photo->date_taken
            );
        }

        return $return;
    }
}