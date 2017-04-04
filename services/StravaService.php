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
    public function getActivities ($limit)
    {
        $activities = $this->stravaApi->getAthleteActivities(null, null, 1, $limit);
        return $this->parseActivities($activities);
    }

    /**
     * Parse service response
     *
     * @param mixed $activities Response received from service
     *
     * @return mixed Parsed response
     */
    protected function parseActivities ($activities)
    {
        $return = array();

        foreach($activities as $activity) {

            $return[] = array(
                'id' => $activity['id'],
                'name' => $activity['name'],
                'location' => $this->getSegmentLocation($activity),
                'date' => $activity['start_date_local'],
                'type' => $activity['type'],
                'distance' => sprintf('%skm', round($activity['distance'] * 0.001, 2)),
                'time' => $this->secondsToTime($activity['moving_time']),
                'url' => sprintf('https://strava.com/activities/%d', $activity['id']),
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
        return $dtF->diff($dtT)->format('%h:%I:%S');
    }

    /**
     * Attempt to retrieve location, from segments
     *
     * @param obj $activity Actitivty object
     *
     * @return string
     */
    protected function getSegmentLocation ($activity) {
        $activityDetail = $this->stravaApi->getActivity($activity['id'], true);
        $location = '';
        if (isset($activityDetail['segment_efforts']) && count($activityDetail['segment_efforts']) > 0) {
          $location = $activityDetail['segment_efforts'][0]['segment']['city'];
        }
        return $location;
    }
}
