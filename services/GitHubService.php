<?php

namespace Api\Services;

/**
 * GitHub Service class
 *
 */
class GitHubService extends AbstractService
{
    /**
     * Prepare service
     * Retrieve config data and prep request URL
     *
     * @return void
     */
    public function configureService ()
    {
        $config = $this->app->config('github');
        $this->url = sprintf('https://api.github.com/users/%s/events/public', $config['handle']);
        $this->configureHeaders();
    }

    /**
     * Prepare additional headers to add to the request
     *
     * @return void
     */
    public function configureHeaders ()
    {
        $config = $this->app->config('github');

        $headers = array(
            'Accept: application/vnd.github.v3+json',
            sprintf('User-Agent: %s', $config['handle'])
        );

        $this->addHeaders($headers);
    }

    /**
     * Perform service request
     *
     * @param int $limit Limit request to required number of responses
     *
     * @return mixed Parsed response
     */
    public function getCommits ($limit)
    {
        $data = $this->request($this->url);
        return $this->parseCommits($data, $limit);
    }

    /**
     * Parse service response
     *
     * @param mixed $data Response received from service
     * @param int $limit Limit request to required number of responses
     *
     * @return mixed Parsed response
     */
    protected function parseCommits ($data, $limit)
    {
        $return = array();

        $commits = json_decode($data);

        $counter = 0;

        foreach($commits as $commit) {

            if ($counter++ == $limit) {
                break;
            }

            // interaction type
            switch ($commit->type) {
                case 'CreateEvent':
                    $type = 'Created';
                    break;
                case 'PushEvent':
                    $type = 'Pushed to';
                    break;
                case 'IssuesEvent':
                    if ($commit->payload->action == 'opened') {
                        $type = sprintf('Created issue %d', $commit->payload->issue->number);
                    } else if ($commit->payload->action == 'closed') {
                        $type = sprintf('Closed issue %d', $commit->payload->issue->number);
                    } else {
                        $type = sprintf('Referenced issue %d', $commit->payload->issue->number);
                    }
                    break;
                case 'IssueCommentEvent':
                    $type = sprintf('Commented on issue %d', $commit->payload->issue->number);
                    break;
                default:
                    $type = 'Worked on';
                    break;
            }

            $return[] = array(
                'type' => $type,
                'repo' => $commit->repo->name,
                'url' => $commit->repo->url,
                'date' => $commit->created_at
            );
        }

        return $return;
    }
}