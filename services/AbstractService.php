<?php

namespace Api\Services;

/**
 * Abstract Service class
 *
 */
abstract class AbstractService
{
    /**
     * Local reference to slim app
     *
     * @param object $app
     */
    protected $app;

    /**
     * Any additional headers to apply to cURL requests
     *
     * @param array $headers
     */
    protected $headers;

    /**
     * Any params that will be appended to all queries
     *
     * @param array $params General request parameters
     */
    protected $params;

    /**
     * Service request base
     * May be appended to with params, etc
     *
     * @param string $apiBase
     */
    protected $apiBase;

    /**
     * Service request URL
     *
     * @param string $url
     */
    protected $url;

    /**
     * Constructor
     * Prepare service for use
     *
     * @return void
     */
    public function __construct ()
    {
        $this->app = \Slim\Slim::getInstance();
        $this->configureService();
    }

    /**
     * Every service needs configuring before use -
     * Set up Service URL, headers, etc
     */
    abstract function configureService ();

    /**
     * Add additional headers for cURL requests
     *
     * @param array $headers
     *
     * @return void
     */
    protected function addHeaders ($headers)
    {
        $this->headers = $headers;
    }

    /**
     * Merge any pre-defined parameters with others specified for the route
     * and request data
     *
     * @param array $params If set, combine with pre-defined params
     *
     * @return mixed Data returned from the service request
     */
    protected function constructRequest ($params = null)
    {
        if ($params) {
            $this->params = array_merge($this->params, $params);
        }
        $url = $this->apiBase . http_build_query($this->params);
        return $this->request($url);
    }

    /**
     * Perform service request
     *
     * @param string $url URL to request data from
     *
     * @return mixed $context Data returned from the service
     */
    protected function request ($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        if ($this->headers) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        }

        $contents = curl_exec($ch);
        curl_close($ch);
        if ($contents) {
            return $contents;
        } else {
            return false;
        }
    }
}