<?php

namespace Api\Middleware;

/**
 * Caching Middleware for Slim
 *
 */
class Cache extends \Slim\Middleware
{
    /**
     * Cache directory
     *
     * @param string $cacheDir directory
     */
    protected $cacheDir;

    /**
     * Time to live
     *
     * @param int $ttl Cache for x minutes
     */
    protected $ttl;


    /**
     * Constructor
     * Create cache middleware
     *
     * @param string $cacheDir
     * @param int $ttl
     *
     * @return void
     */
    public function __construct ($cacheDir = null, $ttl = null)
    {
        // set cache storage limit to initialisation value (or default)
        $this->ttl = ($ttl) ? $ttl : 60 * 5;

        // set cache directory to initialisation value (or default)
        $this->cacheDir = ($cacheDir) ? $cacheDir : (__DIR__ . '/../cache');

        // set up cache
        \FileSystemCache::$cacheDir = $this->cacheDir;
    }

    /**
     * Middleware call method
     * Activated before requests hit the router
     *
     * Returns cached response if cache exists
     * Caches response and passes through to the next step if no cache exists
     *
     * @return void
     */
    public function call ()
    {
        $app = $this->app;
        $request = $app->request;
        $response = $app->response;

        // retrieve request path (with params)
        $url = $request->getResourceUri();
        $params = $request->params();

        // check if cached response exists
        $cachedResponse = $this->fetch($url, $params);

        // cache found, return cached content
        if ($cachedResponse) {
            $response->headers->set('Content-Type', $cachedResponse['content_type']);
            $response->setStatus($cachedResponse['status']);
            $response->setBody($cachedResponse['body']);
            return;
        }

        // cache not found, continue
        $this->next->call();

        // cache successful results for next time
        if ($response->getStatus() === 200) {
            $this->store($url, $params, array(
                'content_type' => $response->headers->get('Content-Type'),
                'status' => $response->getStatus(),
                'body' => $response->getBody()
            ));
        }
    }

    /**
     * Attempt to retrieve a cached response
     *
     * @param string $url The request URL
     * @param array $params Any parameters at the end of the URL
     *
     * @return array/null $data The cached data, if set
     */
    protected function fetch($url, $params)
    {
        $encodedUrl = $this->encodeUrlWithParams($url, $params);
        $key = \FileSystemCache::generateCacheKey($encodedUrl);

        $data = \FileSystemCache::retrieve($key);

        return $data;
    }

    /**
     * Store a repsonse
     *
     * @param string $url The request URL
     * @param array $params Any parameters at the end of the URL
     * @param array $data The data to store
     *
     * @return void
     */
    protected function store($url, $params, $data)
    {
        $encodedUrl = $this->encodeUrlWithParams($url, $params);
        $key = \FileSystemCache::generateCacheKey($encodedUrl);

        \FileSystemCache::store($key, $data, $this->ttl);
    }

    /**
     * Encode a URL with params
     * Allows cache to differentiate requests with different params
     *
     * @param string $url The request URL
     * @param array $params Any parameters at the end of the URL
     *
     * @return string $url URL encoded with params
     */
    private function encodeUrlWithParams ($url, $params)
    {
        if ($params) {
            $url .= '?' . http_build_query($params);
        }
        return $url;
    }

}