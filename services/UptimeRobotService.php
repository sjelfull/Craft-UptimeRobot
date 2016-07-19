<?php
/**
 * Uptime Robot plugin for Craft CMS
 *
 * UptimeRobot Service
 *
 * @author    Fred Carlsen
 * @copyright Copyright (c) 2016 Fred Carlsen
 * @link      http://sjelfull.no
 * @package   UptimeRobot
 * @since     1.0.0
 */

namespace Craft;

class UptimeRobotService extends BaseApplicationComponent
{
    protected $settings;

    public function init ()
    {
        parent::init();

        $plugin = craft()->plugins->getPlugin('uptimerobot');
        if ( !$plugin ) {
            throw new Exception(Craft::t('No plugin exists with the class “{class}”', array( 'class' => 'uptimerobot' )));
        }
        $this->settings = $plugin->getSettings();
    }

    /**
     */
    public function getCheckUrl ()
    {
        $url = UrlHelper::getActionUrl('uptimeRobot/check', array( 'key' => $this->getAccessKey() ));
        $url = str_replace(craft()->config->get('cpTrigger') . '/', '', $url);

        return $url;
    }

    public function getApiKey ()
    {
        return $this->settings->apiKey;
    }

    public function getAccessKey ()
    {
        return $this->settings->accessKey;
    }

}