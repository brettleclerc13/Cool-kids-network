<?php
include 'functions_signup.php';
include 'functions_userdb.php';
include 'functions_login.php';

/*** GENERAL FUNCTIONS ***/

// Queue 20 24 parent theme styles + cool kids theme styles
function coolkids_enqueue_styles() {
    // Enqueuing the parent theme style
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    
    // Enqueuing the child theme style
    wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/style.css', ['parent-style']);
}
add_action('wp_enqueue_scripts', 'coolkids_enqueue_styles');

add_action('wp_footer', 'custom_button_action');

// Install Cool Kids' roles
function coolkids_add_custom_roles() {
    if (!get_role('cool_kid')) {
        add_role('cool_kid', 'Cool Kid', [
            'read' => true,
            'edit_posts' => false,
            'upload_files' => false,
        ]);
    }
    
    if (!get_role('cooler_kid')) {
        add_role('cooler_kid', 'Cooler Kid', [
            'read' => true,
            'edit_posts' => false,
            'upload_files' => false,
            'view_others_profiles' => true,
        ]);
    }
    
    if (!get_role('coolest_kid')) {
        add_role('coolest_kid', 'Coolest Kid', [
            'read' => true,
            'edit_posts' => false,
            'upload_files' => false,
            'view_others_profiles' => true,
            'view_emails_roles' => true,
        ]);
    }
}
add_action('init', 'coolkids_add_custom_roles');

// Hide admin bar for non-admin users
function hide_admin_bar_for_non_admins() {
    if (!current_user_can('administrator'))
        show_admin_bar(false);
}
add_action('after_setup_theme', 'hide_admin_bar_for_non_admins');

//Redirect non-admin users from wp-admin page to the homepage - security function 
function restrict_wp_admin_dashboard_access() {
    if (is_admin() && !current_user_can('administrator') && !(wp_doing_ajax())) {
        wp_redirect(home_url());
        exit;
    }
}
add_action('admin_init', 'restrict_wp_admin_dashboard_access');

/*** BUTTONS ***/

// Stack overflow function which logs the user out directly, bypassing the logout confirmation page
add_action('check_admin_referer', 'logout_without_confirm', 10, 2);
function logout_without_confirm($action)
{
    /**
     * Allow logout without confirmation
     */
    if ($action == "log-out" && !isset($_GET['_wpnonce'])) {
        $redirect_to = isset($_REQUEST['redirect_to']) ? $_REQUEST['redirect_to'] : 'http://localhost:8080';
        $location = str_replace('&amp;', '&', wp_logout_url($redirect_to));
        header("Location: $location");
        die;
    }
}

/*
	Button actions:
	Signup navbar button -> signup page
	Login navbar button -> login page
	Logout navbar button -> logout user
	View your character button -> signup page
*/
function custom_button_action() {
    ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check if user is logged in
            let isLoggedIn = <?php echo json_encode(is_user_logged_in()); ?>;
            
            let loginButton = document.getElementById('login-button-header');
            let signupButton = document.getElementById('signup-button-header');
            let logoutButton = document.getElementById('logout-button-header');
			let startButton = document.getElementById('start-button-homepage');

            if (isLoggedIn) {
                // User is logged in, show logout button and hide login/signup buttons
                if (logoutButton) logoutButton.style.display = 'block';
                if (loginButton) loginButton.style.display = 'none';
                if (signupButton) signupButton.style.display = 'none';
            } else {
                // User is not logged in, show login/signup buttons and hide logout button
                if (logoutButton) logoutButton.style.display = 'none';
                if (loginButton) loginButton.style.display = 'block';
                if (signupButton) signupButton.style.display = 'block';
            }

            // Event listeners for button clicks
            if (signupButton) {
                signupButton.addEventListener('click', function() {
                    window.location.href = '/signup';
                });
            }

            if (loginButton) {
                loginButton.addEventListener('click', function() {
                    window.location.href = '/login';
                });
            }

            if (logoutButton) {
                logoutButton.addEventListener('click', function() {
                    window.location.href = '<?php echo wp_logout_url('/homepage'); ?>';
                });
            }

			if (startButton) {
                startButton.addEventListener('click', function() {
                    if (isLoggedIn)
						window.location.href = '/user-dashboard';
					else
						window.location.href = '/signup';
                });
            }
        });
    </script>
    <?php
}
add_action('wp_footer', 'custom_button_action');
