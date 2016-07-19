/**
 * Uptime Robot plugin for Craft CMS
 *
 * Uptime RobotWidget JS
 *
 * @author    Fred Carlsen
 * @copyright Copyright (c) 2016 Fred Carlsen
 * @link      http://sjelfull.no
 * @package   UptimeRobot
 * @since     1.0.0
 */

var UptimeRobotWidget = {
    init: function() {
        var self = this;

        Craft.postActionRequest('uptimeRobot/stats', self.parseData.bind(self));
    },

    parseData: function( data ) {
        var self = this;
        var $spinner = $('#js-uptimeRobotSpinner');
        var $uptime = $('#js-uptimeRobotUptime');
        var $log = $('#js-uptimeRobotLog');

        $uptime.text( data.alltimeuptimeratio + '%' );

        var logOutput = '';

        for (var i = 0; i < data.log.length; i++) {
            logOutput += self.parseLogItem( data.log[i] );
        }

        $log.html(logOutput);

        // Hide spinner and show results
        $spinner.addClass('hidden');
        $log.parent().removeClass('hidden');
        $uptime.parent().removeClass('hidden');
    },

    parseLogItem: function( data ) {
        var self = this;
        var type = null;

        switch (parseInt(data.type)) {
            case 1:
                type = 'Down';
                break;
            case 2:
                type = 'Up';
                break;
            case 99:
                type = 'Paused';
                break;
            case 98:
                type = 'Started';
                break;
        }

        var output = '<li><span class="uptimerobot-widget-logtype is-'+ type +'">';
        output += type;
        output += '</span><span class="uptimerobot-widget-logdate">';
        output += data.datetime;
        output += '</span></li>';
        return output;
    }
};

UptimeRobotWidget.init();