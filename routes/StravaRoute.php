<?php

namespace Api\Routes;

use Api\Services\StravaService;

/**
 * Strava route
 */
class StravaRoute extends AbstractRoutes
{
    /**
     * Define available routes
     *
     * @param array $routes available routes
     */
    protected $routes = array(
        array(
            'name' => 'Strava latest runs',
            'url' => '/strava/latest-runs',
            'methods' => 'GET',
            'params' => 'limit',
            'classMethod' => 'getRuns'
        ),
    );

    /**
     * Public route
     * Request data from service and respond
     */
    public function getRuns ()
    {
        $stravaService = new StravaService;
        $limit = $this->app->request->params('limit', 1);
        $data = $stravaService->getRuns($limit);
        $this->respond($data);
    }
}