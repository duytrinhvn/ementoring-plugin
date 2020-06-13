<?php

/**
 * @package  eMentoringPlugin
 */

namespace Inc\Base;

use Inc\Base\BaseController;

/**
 * 
 */
class Enqueue extends BaseController
{
    public function register()
    {
        add_action('enqueue_scripts', array($this, 'client_enqueue'));
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue'));
    }

    function client_enqueue()
    {
        wp_enqueue_style('emp_main_style_client', $this->plugin_url . 'assets/main-style-client.css');
        wp_enqueue_script('emp_main_script_client', $this->plugin_url . 'assets/main-script-client.js');
    }

    function admin_enqueue()
    {
        wp_enqueue_style('emp_main_style_admin', $this->plugin_url . 'assets/main-style-admin.css');
        wp_enqueue_script('emp_main_script_admin', $this->plugin_url . 'assets/main-script-admin.js');
    }
}
