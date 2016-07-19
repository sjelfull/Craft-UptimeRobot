<?php
/**
 * Uptime Robot plugin for Craft CMS
 *
 * UptimeRobot Controller
 *
 * @author    Fred Carlsen
 * @copyright Copyright (c) 2016 Fred Carlsen
 * @link      http://sjelfull.no
 * @package   UptimeRobot
 * @since     1.0.0
 */

namespace Craft;

class UptimeRobotController extends BaseController
{

    protected $_endpoint      = 'https://api.uptimerobot.com/getMonitors';
    protected $_cacheDuration = 60 * 5; // Default to 5 minutes

    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     * @access protected
     */
    protected $allowAnonymous = array(
        'actionCheck',
    );

    public function actionStats ()
    {
        if ( $stats = craft()->cache->get('uptimeRobotStats') ) {
            $this->returnJson($stats);
        }

        $plugin = craft()->plugins->getPlugin('uptimerobot');
        if ( !$plugin ) {
            throw new Exception(Craft::t('No plugin exists with the class “{class}”', array( 'class' => 'uptimerobot' )));
        }
        $settings = $plugin->getSettings();

        // Make request to API if not cached
        $client = new \Guzzle\Http\Client();

        $options = array(
            'timeout'         => 30,
            'connect_timeout' => 10,
            'query'           => array(
                'apiKey'               => $settings->apiKey,
                'logs'                 => 1,
                'responseTimes'        => 1,
                'responseTimesAverage' => 1440, // Average response time for the last 24 hours
                'format'               => 'json',
            ),
        );

        $request = $client->post($this->_endpoint, null, null, $options);

        // Potentially long-running request, so close session to prevent session blocking on subsequent requests.
        // craft()->session->close();

        $response = $request->send();

        if ( $response->isSuccessful() ) {
            $body = $response->getBody(true);

            // Parse json to remove callback
            $match = preg_match("/(jsonUptimeRobotApi\\()(.+)(\\))/uim", $body, $matches);

            if ( !$match ) {
                throw new Exception(Craft::t('Problem with parsing JSON from Uptime Robot API: “{json}”', array( 'json' => $body )));
            }

            // Cast to json
            $data = json_decode($matches[2], true);

            if ( count($data['monitors']) === 0 ) {
                throw new Exception(Craft::t('No monitors returned from Uptime Robot API'));
            }

            $data = $data['monitors']['monitor'][0];

            craft()->cache->set('uptimeRobotStats', $data, $this->_cacheDuration);

            $this->returnJson($data);
        }

    }

    /**
     */
    public
    function actionCheck ()
    {
        $plugin = craft()->plugins->getPlugin('uptimerobot');
        if ( !$plugin ) {
            throw new Exception(Craft::t('No plugin exists with the class “{class}”', array( 'class' => 'uptimerobot' )));
        }
        $settings = $plugin->getSettings();
        $key      = craft()->request->getParam('key');

        if ( $settings->accessKey !== $key ) {
            throw new Exception(Craft::t('Access key “{key}” was not valid.', array( 'key' => $key )));
        }

        echo 'UP!';

        craft()->end();
    }
}