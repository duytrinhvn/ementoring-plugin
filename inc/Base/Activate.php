<?php

/**
 * @package  eMentoringPlugin
 */

namespace Inc\Base;

class Activate
{
    public static function activate()
    {
        flush_rewrite_rules();

        // Create plugin's tables
        self::create_tables();

        // Create user roles
        self::create_user_roles();

        // Create capabilities and Assign capabilities to roles
        self::create_capabilities();

        // Seed users
        self::seed_users();
    }

    public static function create_user_roles()
    {
        add_role('emp_mentor', "EMP Mentor");
        add_role('emp_admin', "EMP Administrator");
        add_role('emp_student', "EMP Student");
    }

    private static function insert_user($username, $email, $password, $display_name, $role)
    {
        /* Do nothing if the username exists. */
        if (username_exists($username))
            return;
        /* Set up the user data. */
        $userdata = array(
            'user_login' => $username,
            'user_email' => $email,
            'user_pass' => $password,
            'display_name' => $display_name,
            'role' => $role
        );
        /* Create the user. */
        $user = wp_insert_user($userdata);
        /* If the user wasn't created, display the error message. */
        if (is_wp_error($user))
            echo $result->get_error_message();
    }

    public static function seed_users()
    {
        self::insert_user('emp_admin1', 'emp_admin1@example.com', 'admin1Pass', 'EMP Admin #1', 'emp_admin');
        self::insert_user('emp_mentor1', 'emp_mentor1@example.com', 'mentor1Pass', 'EMP Mentor #1', 'emp_mentor');
        // get user id by username
        $mentor1_id = get_user_by('login', 'emp_mentor1')->ID;
        // add metadatas
        add_user_meta($mentor1_id, 'interests', ['math', 'french', 'computer science'], true);
        add_user_meta($mentor1_id, 'company', 'Columbia college', true);

        self::insert_user('emp_student1', 'emp_student1@example.com', 'student1Pass', 'EMP Student #1', 'emp_student');
        self::insert_user('emp_student2', 'emp_student2@example.com', 'student2Pass', 'EMP Student #2', 'emp_student');
        // get user id by username
        $student1_id = get_user_by('login', 'emp_student1')->ID;
        $student2_id = get_user_by('login', 'emp_student2')->ID;
        // add metadatas
        add_user_meta($student1_id, 'interests', ['math', 'spanish', 'programming'], true);
        add_user_meta($student1_id, 'school', 'Holululu highschool', true);

        add_user_meta($student2_id, 'interests', ['geography', 'technology', 'history'], true);
        add_user_meta($student2_id, 'school', 'Tokyo highschool', true);
    }

    public static function create_capabilities()
    {
        // Create capabilities
        $admin_caps = array(
            'read_connections',
            'read_all_conversations',
            'create_connections',
            'delete_connections',
            'create_users',
            'edit_users',
            'delete_users',
            'read_all_users',
            'create_topics',
            'edit_topics',
            'read_all_topics',
            'delete_topics',
            'mark_archived',
        );

        $client_caps = array(
            'send_messages',
            'upload_files',
            'edit_personal_profile'
        );

        // Assign capabilities to roles
        $admin_role = get_role('emp_admin');
        foreach ($admin_caps as $cap) {
            $admin_role->add_cap($cap);
        }

        $mentor_role = get_role('emp_mentor');
        foreach ($client_caps as $cap) {
            $mentor_role->add_cap($cap);
        }

        $student_role = get_role('emp_student');
        foreach ($client_caps as $cap) {
            $student_role->add_cap($cap);
        }
    }

    public static function create_tables()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        // TODO: Replace these local variables with constants from Base Controller
        $nameTopicsTable = $wpdb->base_prefix . 'emp_topics';
        $nameRoomsTable = $wpdb->base_prefix . 'emp_rooms';
        $nameRoomTopicTable = $wpdb->base_prefix . 'emp_roomtopic';
        $nameMessagesTable = $wpdb->base_prefix . 'emp_messages';
        $nameParticipantsTable = $wpdb->base_prefix . 'emp_participants';
        $nameLoginDetailsTable = $wpdb->base_prefix . 'emp_logindetails';
        $nameUsersTable = $wpdb->base_prefix . 'users';

        $sqlTopicsTable = "CREATE TABLE `{$nameTopicsTable}` (
            id int NOT NULL, 
            name varchar(100) NOT NULL, 
            description TEXT, 
            PRIMARY KEY (id) 
            ) $charset_collate;";

        $sqlRoomsTable = "CREATE TABLE `{$nameRoomsTable}` (
            id int NOT NULL, 
            PRIMARY KEY (id) 
            ) $charset_collate;";

        $sqlRoomTopicTable = "CREATE TABLE `{$nameRoomTopicTable}` (
            id int NOT NULL, 
            room_id int NOT NULL,
            topic_id int NOT NULL,
            completion tinyint(1) NOT NULL DEFAULT '0', 
            FOREIGN KEY (room_id) REFERENCES {$nameRoomsTable}(id),
            FOREIGN KEY (topic_id) REFERENCES {$nameTopicsTable}(id),
            PRIMARY KEY (id) 
            ) $charset_collate;";

        $sqlMessagesTable = "CREATE TABLE `{$nameMessagesTable}` (
            id int NOT NULL, 
            room_id int NOT NULL,
            from_user_id bigint UNSIGNED NOT NULL,
            to_user_id bigint UNSIGNED NOT NULL,
            message text NOT NULL,
            timestamp timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            status boolean NOT NULL,
            FOREIGN KEY (room_id) REFERENCES {$nameRoomsTable}(id),
            FOREIGN KEY (from_user_id) REFERENCES {$nameUsersTable}(ID),
            FOREIGN KEY (to_user_id) REFERENCES {$nameUsersTable}(ID),
            PRIMARY KEY (id)
            ) $charset_collate;";

        $sqlParticipantsTable = "CREATE TABLE `{$nameParticipantsTable}` (
            id int NOT NULL, 
            room_id int NOT NULL,
            user_id bigint UNSIGNED NOT NULL,
            FOREIGN KEY (room_id) REFERENCES {$nameRoomsTable}(id),
            FOREIGN KEY (user_id) REFERENCES {$nameUsersTable}(ID),
            PRIMARY KEY (id)
            ) $charset_collate;";

        $sqlLoginDetailsTable = "CREATE TABLE `{$nameLoginDetailsTable}` (
            id int NOT NULL, 
            user_id bigint UNSIGNED NOT NULL,
            last_activity timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            is_type enum('no','yes') NOT NULL,
            FOREIGN KEY (user_id) REFERENCES {$nameUsersTable}(ID),
            PRIMARY KEY (id)
            ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        if ($wpdb->get_var("SHOW TABLES LIKE '{$nameTopicsTable}';") != $nameTopicsTable) {
            dbDelta($sqlTopicsTable);
        }

        if ($wpdb->get_var("SHOW TABLES LIKE '{$nameRoomsTable}';") != $nameRoomsTable) {
            dbDelta($sqlRoomsTable);
        }

        if ($wpdb->get_var("SHOW TABLES LIKE '{$nameRoomTopicTable}';") != $nameRoomTopicTable) {
            dbDelta($sqlRoomTopicTable);
        }

        if ($wpdb->get_var("SHOW TABLES LIKE '{$nameMessagesTable}';") != $nameMessagesTable) {
            dbDelta($sqlMessagesTable);
        }

        if ($wpdb->get_var("SHOW TABLES LIKE '{$nameParticipantsTable}';") != $nameParticipantsTable) {
            dbDelta($sqlParticipantsTable);
        }

        if ($wpdb->get_var("SHOW TABLES LIKE '{$nameLoginDetailsTable}';") != $nameLoginDetailsTable) {
            dbDelta($sqlLoginDetailsTable);
        }
    }
}
