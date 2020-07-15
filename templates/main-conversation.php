<?php
$roomId = $_GET['roomId'];
// TODO: Remove duplication of $current_user_id variable
$current_user_id = wp_get_current_user()->ID;
$oppositeUser;
global $wpdb;

$oppositeUserResult = $wpdb->get_results(
    $wpdb->prepare(
        "
        SELECT p.user_id, u.display_name
        FROM {$this->nameParticipantsTable} AS p
        JOIN {$this->nameUsersTable} AS u
        ON p.user_id = u.ID
        WHERE room_id = %d AND user_id != %d;
        ",
        $roomId,
        $current_user_id
    )
);

if ($oppositeUserResult != null) {
    $oppositeUser = $oppositeUserResult[0];
}

// TODO: Remove duplication
// Get user_id and user's display_name of users who have connection with the current user
$roomResults = $wpdb->get_results(
    $wpdb->prepare(
        "
        SELECT room_id, user_id, display_name
        FROM {$this->nameParticipantsTable} AS p
        JOIN {$this->nameUsersTable} AS u
        ON p.user_id = u.ID
        WHERE p.ROOM_ID IN (
            SELECT room_id
            FROM {$this->nameParticipantsTable}
            WHERE user_id = %d
        )
            AND p.user_id != %d
        ;
        ",
        $current_user_id,
        $current_user_id
    )
);


// Get topics of the current room and it's completion status
$topicResults = $wpdb->get_results(
    $wpdb->prepare(
        "
        SELECT rt.id, topic_id, completion, name
        FROM {$this->nameRoomTopicTable} AS rt
        JOIN {$this->nameTopicsTable} AS t
        ON rt.topic_id = t.id
        WHERE room_id = %d;
        ",
        $roomId
    )
);


// Get messages of the current room
$messageResults = $wpdb->get_results(
    $wpdb->prepare(
        "
        SELECT room_id, from_user_id, message, TIMESTAMP, STATUS, display_name
        FROM {$this->nameMessagesTable} AS m
        JOIN {$this->nameUsersTable} AS u
        ON m.from_user_id = u.ID
        WHERE room_id = %d
        ORDER BY TIMESTAMP;
        ",
        $roomId
    )
);


?>

<div class="container">
    <div class="emp-nav">
        <div class="nav-container">
            <h4 id="nav-header-1">Main Conversation</h4>
            <p id="nav-header-2"><?php echo $oppositeUser->display_name; ?></p>
        </div>

        <div class="nav-profile-avatar">
            <img src="https://cdn3.vectorstock.com/i/1000x1000/30/97/flat-business-man-user-profile-avatar-icon-vector-4333097.jpg" alt="profile avatar" />
        </div>
    </div>
    <div id="conversation-container">
        <div id="rooms-column">

            <?php
            foreach ($roomResults as $result) {
            ?>

                <div style="text-decoration: none;" class="rooms" id="rooms-<?php echo $result->room_id; ?>">
                    <img src="https://www.shareicon.net/data/512x512/2016/05/24/770137_man_512x512.png" alt="Profile Avatar" />
                    <p class="emp-ptext"><?php echo $result->display_name; ?></p>
                </div>

            <?php } ?>
        </div>

        <div id="chat-column">

            <?php
            foreach ($messageResults as $message) {
            ?>
                <div class="message-box">
                    <div class="message-avatar">
                        <img src="https://cdn1.vectorstock.com/i/1000x1000/19/45/user-avatar-icon-sign-symbol-vector-4001945.jpg" alt="Profile Avatar" />
                    </div>

                    <div class="message-info">
                        <p class="emp-ptext"><span <?php
                                                    if ($message->from_user_id == $current_user_id) echo "style='color: rgb(0, 127, 184)'";
                                                    ?> class="message-author"><?php echo $message->display_name; ?></span> <span class="message-timestamp">5/23/2020</span></p>
                        <p class="emp-ptext message-content"><?php echo $message->message; ?></p>
                    </div>
                </div>

            <?php } ?>
            <!-- <div class="message-box">
                <div class="message-avatar">
                    <img src="https://png.pngtree.com/png-clipart/20190924/original/pngtree-user-vector-avatar-png-image_4830521.jpg" alt="Profile Avatar" />
                </div>

                <div class="message-info">
                    <p class="emp-ptext"><span class="message-author">Micheal</span> <span class="message-timestamp">3 mins ago</span></p>
                    <p class="emp-ptext message-content">So far so good!</p>
                    <p class="emp-ptext message-content">Yes I am still living in Hamilton</p>
                    <p class="emp-ptext message-content">How about you?</p>
                </div>
            </div> -->
        </div>

        <div id="topics-column">
            <div id="topics-header">
                <h4 id="topics-header-text">Conversation Starter</h4>
            </div>

            <div id="topics-list">

                <?php
                foreach ($topicResults as $topic) {
                ?>

                    <div class="topics">
                        <div class="topics-title">
                            <p class="title-p"><span class="font-weight-bold">Topic: </span> <?php echo $topic->name; ?> </p>
                        </div>

                        <div class="topics-checkbox">
                            <input type="checkbox" name="checkbox<?php echo $topic->id; ?>" id="checkbox<?php echo $topic->id; ?>" class="css-checkbox" <?php
                                                                                                                                                        $isChecked = $topic->completion == 0 ? "" : "checked";
                                                                                                                                                        echo $isChecked;
                                                                                                                                                        ?>>
                            <label for="checkbox<?php echo $topic->id; ?>" class="css-label"></label>
                        </div>
                    </div>

                <?php } ?>

            </div>

        </div>

        <div id="message-form">
            <input placeholder="Type a message..." type="text" id="message-textbox" />
            <button id="message-button">Send</button>
        </div>
    </div>
</div>