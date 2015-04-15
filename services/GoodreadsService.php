<?php

namespace Api\Services;

/**
 * Goodreads Service class
 *
 */
class GoodreadsService extends AbstractService
{
    /**
     * Service request base
     * Will be appended to with params, etc
     *
     * @param string $apiBase
     */
    protected $apiBase = 'https://www.goodreads.com/review/list_rss/%d';

    /**
     * Prepare service
     * Retrieve config data and prep params
     *
     * @return void
     */
    public function configureService ()
    {
        $config = $this->app->config('goodreads');
        $this->url = sprintf($this->apiBase, $config);

    }

    /**
     * Perform service request
     *
     * @param int $limit Limit request to required number of responses
     *
     * @return mixed Parsed response
     */
    public function getBooks ($limit)
    {
        $data = $this->request($this->url);
        return $this->parseBooks($data, $limit);
    }

    /**
     * Parse service response
     *
     * @param mixed $data Response received from service
     * @param int $limit Limit request to required number of responses
     *
     * @return mixed Parsed response
     */
    protected function parseBooks ($data, $limit)
    {
        $return = array();

        $books = simplexml_load_string($data);

        $counter = 0;

        foreach($books->channel->item as $book) {

            if ($counter++ == $limit) {
                break;
            }

            $return[] = array(
                'title' => (string) $book->title,
                'author' => (string) $book->author_name,
                'image' => (string) $book->book_small_image_url,
                'date' => (string) $book->user_date_added,
                'rating' => (int) $book->user_rating
            );
        }

        return $return;
    }
}