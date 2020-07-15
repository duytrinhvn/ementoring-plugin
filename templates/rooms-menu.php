<?php
$current_user = wp_get_current_user();
$current_user_id = wp_get_current_user()->ID;
$user_ids = array();
$user_ids_placeholders = array();

$displayed_rooms_list = array();

global $wpdb;


// Get user_id of users who have connection with the current user
$results = $wpdb->get_results(
    $wpdb->prepare(
        "
        SELECT USER_ID
        FROM {$this->nameParticipantsTable} 
        WHERE ROOM_ID IN (
            SELECT ROOM_ID 
            FROM {$this->nameParticipantsTable}
            WHERE USER_ID = %d
        ) 
            AND USER_ID != %d
        ;
        ",
        $current_user_id,
        $current_user_id
    )
);

foreach ($results as $id) {
    array_push($user_ids, $id->USER_ID);
    array_push($user_ids_placeholders, '%d');
}

// Convert Array to string with ', ' separator
$user_ids_placeholders = implode(', ', $user_ids_placeholders);

// Duplicate and self-merge $user_ids array
$user_ids = array_merge($user_ids, $user_ids);


// Get user display names and user last message based on user_id 
$results = $wpdb->get_results(
    $wpdb->prepare(
        "
        SELECT m.room_id, m.from_user_id, m.to_user_id, m.message, m.timestamp, m.status, u.display_name
        FROM {$this->nameMessagesTable} AS m
        JOIN {$this->nameUsersTable} AS u
        ON m.from_user_id = u.ID
        WHERE m.from_user_id IN ({$user_ids_placeholders})
            OR m.to_user_id IN ({$user_ids_placeholders})
        ORDER BY m.timestamp DESC, room_id;
        ",
        $user_ids
    )
);

// var_dump($results);
// die();

?>

<div class="container">
    <div class="emp-nav">
        <div class="nav-container">
            <h4 id="nav-header-1">Hello, <?php echo $current_user->display_name; ?></h4>
            <p id="nav-header-2"><?php echo ucfirst($current_user->roles[0]); ?></p>
        </div>

        <div class="nav-profile-avatar">
            <img src="https://cdn3.vectorstock.com/i/1000x1000/30/97/flat-business-man-user-profile-avatar-icon-vector-4333097.jpg" alt="profile avatar" />
        </div>
    </div>
    <div id="rooms-container">
        <!-- <div href="#" class="room-card">
            <div class="room-info">
                <h3>Student: Johnny Wang</h3>
                <p>You: You're doing a great job, Johnny! - 2hrs ago</p>
            </div>
            <div class="room-avatar">
                <img src="https://www.shareicon.net/data/512x512/2016/07/26/802043_man_512x512.png" alt="profile avatar" />
            </div>
        </div> -->
        <?php
        foreach ($results as $room) {
            $opposite_user_id = $room->to_user_id != $current_user_id ? $room->to_user_id : $room->from_user_id;
            $opposite_user = get_userdata($opposite_user_id);

            // Check if room already displayed
            if (in_array($room->room_id, $displayed_rooms_list) == false) {
                array_push($displayed_rooms_list, $room->room_id);

                // $last_message = 
        ?>

                <div href="#" class="room-card" id="room-card-<?php echo $room->room_id; ?>">
                    <div class="room-info">
                        <h3>Student: <?php echo $opposite_user->display_name; ?></h3>
                        <p><?php echo $room->from_user_id == $current_user_id ? 'You' : $room->display_name; ?>: <?php echo $room->message; ?></p>
                    </div>
                    <div class="room-avatar">
                        <img src="https://www.shareicon.net/data/512x512/2016/07/26/802043_man_512x512.png" alt="profile avatar" />
                    </div>
                </div>

        <?php
            }
        }
        ?>
        <!-- <div href="#" class="room-card">
            <div class="room-info">
                <h3>Student: Johnny Wang</h3>
                <p>You: You're doing a great job, Johnny! - 2hrs ago</p>
            </div>
            <div class="room-avatar">
                <img src="https://www.shareicon.net/data/512x512/2016/07/26/802043_man_512x512.png" alt="profile avatar" />
            </div>
        </div> -->
    </div>
</div>