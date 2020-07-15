<?php

/**
 * @package  eMentoringPlugin
 */

namespace Inc\Base;

use Inc\Base\BaseController;

/**
 * 
 */
class ChatController extends BaseController
{
    public function register()
    {
        add_shortcode('chat', array($this, 'ementoring_shortcode'));
    }

    public function ementoring_shortcode()
    {
    }

    public function change_topic_completion()
    {
        global $wpdb;
        $checked = $_POST['checked'];
        $checked = $checked == 1 ? 1 : 0;
        $roomTopicId = $_POST['roomTopicId'];

        $topicCompletionResults = $wpdb->get_results(
            $wpdb->prepare(
                "
                UPDATE wp_emp_roomtopic
                SET COMPLETION = %d
                WHERE id = %d;
                ",
                $checked,
                $roomTopicId
            )
        );

        // TODO: Error handler for topicCompletionResults

        die();
    }
}
