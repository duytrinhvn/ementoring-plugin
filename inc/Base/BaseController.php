<?php

/**
 * @package  eMentoringPlugin
 */

namespace Inc\Base;

class BaseController
{
    public $plugin_path;

    public $plugin_url;

    public $plugin;

    public $managers = array();

    public $nameTopicsTable;

    public $nameRoomsTable;

    public $nameRoomTopicTable;

    public $nameMessagesTable;

    public $nameParticipantsTable;

    public $nameLoginDetailsTable;

    public $nameUsersTable;

    public function __construct()
    {
        global $wpdb;
        $this->plugin_path = plugin_dir_path(dirname(__FILE__, 2));
        $this->plugin_url = plugin_dir_url(dirname(__FILE__, 2));
        $this->plugin = plugin_basename(dirname(__FILE__, 3)) . '/ementoring-plugin.php';

        $this->nameTopicsTable = $wpdb->base_prefix . 'emp_topics';
        $this->nameRoomsTable = $wpdb->base_prefix . 'emp_rooms';
        $this->nameRoomTopicTable = $wpdb->base_prefix . 'emp_roomtopic';
        $this->nameMessagesTable = $wpdb->base_prefix . 'emp_messages';
        $this->nameParticipantsTable = $wpdb->base_prefix . 'emp_participants';
        $this->nameLoginDetailsTable = $wpdb->base_prefix . 'emp_logindetails';
        $this->nameUsersTable = $wpdb->base_prefix . 'users';
    }
}

$bc = new BaseController();
