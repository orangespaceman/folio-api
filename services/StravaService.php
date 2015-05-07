<?php

namespace Api\Services;

use Pest;
use Strava\API\Client;
use Strava\API\Exception;
use Strava\API\Service\REST;

/**
 * Strava Service class
 *
 */
class StravaService extends AbstractService
{
    /**
     * Local copy of relevant configuration settings
     *
     * @param array $config
     */
    private $config;

    /**
     * Instance of the Strava API wrapper
     *
     * @param object $stravaApi
     */
    private $stravaApi;

    /**
     * Prepare service
     * Retrieve config data and configure API library
     *
     * @return void
     */
    public function configureService ()
    {
        $this->config = $this->app->config('strava');

        // connect to service
        $adapter = new Pest('https://www.strava.com/api/v3');
        $service = new REST($this->config['access_token'], $adapter);
        $this->stravaApi = new Client($service);
    }

    /**
     * Perform service request
     *
     * @param int $limit Limit request to required number of responses
     *
     * @return mixed Parsed response
     */
    public function getRuns ($limit)
    {
        $data = $this->stravaApi->getAthleteActivities(null, null, 1, $limit);
        return $this->parseRuns($data);
    }

    /**
     * Parse service response
     *
     * @param mixed $data Response received from service
     *
     * @return mixed Parsed response
     */
    protected function parseRuns ($data)
    {
        $return = array();

        foreach($data as $run) {

            $return[] = array(
                'id' => $run['id'],
                'location' => $run['location_city'],
                'date' => $run['start_date_local'],
                'distance' => sprintf('%skm', round($run['distance'] * 0.001, 2)),
                'time' => $this->secondsToTime($run['moving_time']),
                'url' => sprintf('https://strava.com/activities/%d', $run['id']),
            );
        }

        return $return;
    }

    /**
     * Turn time in seconds into nicer format
     *
     * @param int $seconds Time in seconds
     *
     * @return string
     */
    protected function secondsToTime ($seconds) {
        $dtF = new \DateTime("@0");
        $dtT = new \DateTime("@$seconds");
        return $dtF->diff($dtT)->format('%h:%i:%s');
    }
}