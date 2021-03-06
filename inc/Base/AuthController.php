<?php

/**
 * @package  
 */

namespace Inc\Base;

use Inc\Base\BaseController;

/**
 *
 */
class AuthController extends BaseController
{
    public $screen;

    public function register()
    {
        // Add enqueue hook
        add_action('wp_enqueue_scripts', array($this, 'enqueue'));

        // Add template display hook
        add_shortcode('chat', array($this, 'emp_shortcode'));
        // Add ajax handler hook
        add_action('wp_ajax_nopriv_emp_login_process', array($this, 'login'));

        add_action('wp_ajax_emp_chat_goto', array($this, 'goto_chat'));

        add_action('wp_ajax_emp_topic_completion', array($this, 'change_topic_completion'));
    }

    public function emp_shortcode()
    {
        ob_start();
        if (is_user_logged_in()) {
            require_once($this->plugin_path . 'templates/rooms-menu.php');
        } else {
            require_once($this->plugin_path . 'templates/login.php');
        }
        return ob_get_clean();
    }

    public function enqueue()
    {
        // Enqueue custom script
        wp_enqueue_script('authscript', $this->plugin_url . 'assets/client/auth.js');

        // Get current page protocol
        $protocol = isset($_SERVER["HTTPS"]) ? 'https://' : 'http://';
        // Output admin-ajax.php URL with same protocol as current page
        $params = array(
            'ajaxurl' => admin_url('admin-ajax.php', $protocol)
        );
        wp_localize_script('authscript', 'emp_params', $params);
    }

    public function login()
    {
        // Check nonce
        // check_ajax_referer('ajax-login-nonce', 'emp_auth');

        // // Check login credentials
        $info = array();
        $info['user_login'] = $_POST['username'];
        $info['user_password'] = $_POST['password'];
        $info['remember'] = true;

        $user_signon = wp_signon($info, false);

        // Response to client
        if (is_wp_error($user_signon)) {
            echo json_encode(
                array(
                    'status' => false,
                    'message' => 'Wrong username or password'
                )
            );

            die();
        }

        echo json_encode(
            array(
                'status' => true,
                'message' => 'Login successful, redirecting...'
            )
        );

        die();
    }

    public function goto_chat()
    {

        ob_start();
        echo '<script type="text/javascript" src="' . $this->plugin_url . 'assets/client/conversation-script.js' . '"></script>';
        require_once($this->plugin_path . 'templates/main-conversation.php');
        echo ob_get_clean();
        die();
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
