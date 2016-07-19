<?php
/**
 * Uptime Robot plugin for Craft CMS
 *
 * UptimeRobot Widget
 *
 * @author    Fred Carlsen
 * @copyright Copyright (c) 2016 Fred Carlsen
 * @link      http://sjelfull.no
 * @package   UptimeRobot
 * @since     1.0.0
 */
namespace Craft;
class UptimeRobotWidget extends BaseWidget
{
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
    public function getBodyHtml ()
    {
        // Include our Javascript & CSS
        craft()->templates->includeCssResource('uptimerobot/css/widgets/UptimeRobotWidget.css');
        craft()->templates->includeJsResource('uptimerobot/js/widgets/UptimeRobotWidget.js');

        /* -- Variables to pass down to our rendered template */
        $variables             = array();
        $variables['settings'] = $this->getSettings();

        return craft()->templates->render('uptimerobot/widgets/UptimeRobotWidget_Body', $variables);
    }

    /**
     * @return int
     */
    public function getColspan ()
    {
        return 1;
    }
}