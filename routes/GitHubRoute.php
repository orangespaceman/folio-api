<?php

namespace Api\Routes;

use Api\Services\GitHubService;

/**
 * GitHub route
 */
class GitHubRoute extends AbstractRoutes
{
    /**
     * Define available routes
     *
     * @param array $routes available routes
     */
    protected $routes = array(
        array(
            'name' => 'GitHub latest commits',
            'url' => '/github/commits',
            'methods' => 'GET',
            'params' => 'limit',
            'classMethod' => 'getCommits'
        ),
    );

    /**
     * Public route
     * Request data from service and respond
     */
    public function getCommits ()
    {
        $githubService = new GitHubService;
        $limit = $this->app->request->params('limit', 1);
        $data = $githubService->getCommits($limit);
        $this->respond($data);
    }
}