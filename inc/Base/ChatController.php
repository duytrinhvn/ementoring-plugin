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
        $output = "<h1>Chat Shortcode here!</h1>";
        return $output;
    }
}
