<?php
/**
 * Uptime Robot plugin for Craft CMS
 *
 * Integrates with Uptime Robot to monitor the health of your Craft site.
 *
 * @author    Fred Carlsen
 * @copyright Copyright (c) 2016 Fred Carlsen
 * @link      http://sjelfull.no
 * @package   UptimeRobot
 * @since     1.0.0
 */

namespace Craft;

class UptimeRobotPlugin extends BasePlugin
{
    /**
     * @return mixed
     */
    public function init ()
    {
    }

    /**
     * @return mixed
     */
    public function getName ()
    {
        return Craft::t('Uptime Robot');
    }

    /**
     * @return mixed
     */
    public function getDescription ()
    {
        return Craft::t('Integrates with Uptime Robot to monitor the health of your Craft site.');
    }

    /**
     * @return string
     */
    public function getDocumentationUrl ()
    {
        return 'https://github.com/sjelfull/Craft-UptimeRobot/blob/master/README.md';
    }

    /**
     * @return string
     */
    public function getReleaseFeedUrl ()
    {
        return 'https://raw.githubusercontent.com/sjelfull/Craft-UptimeRobot/master/releases.json';
    }

    /**
     * @return string
     */
    public function getVersion ()
    {
        return '1.0.1';
    }

    /**
     * @return string
     */
    public function getSchemaVersion ()
    {
        return '1.0.0';
    }

    /**
     * @return string
     */
    public function getDeveloper ()
    {
        return 'Fred Carlsen';
    }

    /**
     * @return string
     */
    public function getDeveloperUrl ()
    {
        return 'http://sjelfull.no';
    }

    /**
     */
    public function onAfterInstall ()
    {
        // Set random key if not present
        $this->setSettings([
            'accessKey' => StringHelper::randomString()
        ]);

        craft()->plugins->savePluginSettings($this, $this->getSettings());
    }


    /**
     * @return array
     */
    protected function defineSettings ()
    {
        return array(
            'apiKey'    => array( AttributeType::String, 'label' => 'Monitor Specific API Key', 'default' => '' ),
            'accessKey' => array( AttributeType::String, 'label' => 'Access key', 'default' => '' ),
        );
    }

    /**
     * @return mixed
     */
    public function getSettingsHtml ()
    {
        return craft()->templates->render('uptimerobot/UptimeRobot_Settings', array(
            'settings' => $this->getSettings(),
            'checkUrl' => craft()->uptimeRobot->getCheckUrl(),
        ));
    }

    /**
     * @param mixed $settings The Widget's settings
     *
     * @return mixed
     */
    public function prepSettings ($settings)
    {
        // Modify $settings here...

        return $settings;
    }

}