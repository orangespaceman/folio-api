<?php

namespace Api\Routes;

/**
 * Error route
 *
 */
class ErrorRoute extends AbstractRoutes
{
    /**
     * Create a 404 route not found error
     *
     * @return void
     */
    public function getRouteNotFound ()
    {
        $this->respond('Route not found', 404);
    }
}