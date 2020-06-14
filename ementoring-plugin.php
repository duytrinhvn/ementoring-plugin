<?php

/**
 * @package  eMentoringPlugin
 */
/*
Plugin Name: e-Mentoring Plugin
Description: A chat real-time chat plugin that connects students and mentors 
Version: 0.0.1
Author: Jeff (Khac Duy) Trinh
License: GPLv2 or later
Text Domain: ementoring-plugin
*/

// If this file is called firectly, abort!!!
defined('ABSPATH') or die('No direct access please!');

// Require once the Composer Autoload
if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
    require_once dirname(__FILE__) . '/vendor/autoload.php';
}

/**
 * The code that runs during plugin activation
 */
function activate_ementoring_plugin()
{
    Inc\Base\Activate::activate();
}
register_activation_hook(__FILE__, 'activate_ementoring_plugin');

/**
 * The code that runs during plugin deactivation
 */
function deactivate_ementoring_plugin()
{
    Inc\Base\Deactivate::deactivate();
}
register_deactivation_hook(__FILE__, 'deactivate_ementoring_plugin');

/**
 * Initialize all the core classes of the plugin
 */
if (class_exists('Inc\\Init')) {
    Inc\Init::register_services();
}

// global $shortcode_tags;
// var_dump($shortcode_tags);
// die();
