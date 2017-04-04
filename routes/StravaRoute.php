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
            'name' => 'Strava latest activities',
            'url' => '/strava/latest-activities',
            'methods' => 'GET',
            'params' => 'limit',
            'classMethod' => 'getActivities'
        ),
    );

    /**
     * Public route
     * Request data from service and respond
     */
    public function getActivities ()
    {
        $stravaService = new StravaService;
        $limit = $this->app->request->params('limit', 1);
        $data = $stravaService->getActivities($limit);
        $this->respond($data);
    }
}
