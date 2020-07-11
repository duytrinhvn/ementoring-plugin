<div class="container">
    <div id="login-nav" class="emp-nav">
        <h4 id="nav-logo">e-Mentoring Platform</h4>
    </div>
    <div id="login-container">
        <form id="login-form" action="#" method="post" data-url="<?php echo admin_url('admin-ajax.php'); ?>">

            <label class="login-labels" for="">Username</label>
            <input type="text" name="username" />

            <label class="login-labels" for="">Password</label>
            <a href="#" id="forget-password-link">Forget your password?</a>
            <input type="password" name="password" />

            <input id="login-button" type="submit" value="Login" name="submit">

            <input type="hidden" name="action" value="emp_login">
            <?php wp_nonce_field('ajax-login-nonce', 'emp_auth'); ?>

            <p id="status" data-message="status"></p>
        </form>
    </div>
</div>