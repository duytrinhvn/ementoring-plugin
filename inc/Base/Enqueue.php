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
        add_action('wp_enqueue_scripts', array($this, 'client_enqueue'));
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue'));
    }

    function client_enqueue()
    {
        wp_enqueue_style('emp_main_style_client', $this->plugin_url . 'assets/client/main-style-client.css');
        wp_enqueue_script('emp_main_script_client', $this->plugin_url . 'assets/client/main-script-client.js');
        wp_enqueue_script('jquery-3.4.0', 'http://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js');
        wp_enqueue_style('bootstrap-css-4.5.0', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css');
    }

    function admin_enqueue()
    {
        wp_enqueue_style('emp_main_style_admin', $this->plugin_url . 'assets/admin/main-style-admin.css');
        wp_enqueue_script('emp_main_script_admin', $this->plugin_url . 'assets/admin/main-script-admin.js');
    }
}
