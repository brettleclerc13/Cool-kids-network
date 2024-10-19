<?php

function handle_login_form_submission() {
	// Check if correct WP Form is used (login form)
	if (isset($_POST['wpforms']['id']) && $_POST['wpforms']['id'] == 102) {
		// Check if the form is submitted with both email and password field set
		if (isset($_POST['wpforms']['submit']) && isset($_POST['wpforms']['fields'][1]) && isset($_POST['wpforms']['fields'][2])) {
			$password = $_POST['wpforms']['fields'][2];
			
			// Password check before logging in user
			if ($password !== 'password123') {
				$_SESSION['login_error'] = 'Incorrect password, please type in "password123" as the password';
				wp_redirect(home_url('/login'));
				return ;
			}

			$email = sanitize_email($_POST['wpforms']['fields'][1]);
			// Check if the email is already registered, if the user exists or not
			$user = get_user_by('email', $email);
			if ($user) {
				// Log in the non-admin user
				wp_set_current_user($user->ID);
				wp_set_auth_cookie($user->ID);
				wp_redirect(home_url('/user-dashboard'));
			}
			else {
				wp_redirect(home_url('/signup'));
			}
		}
	}
}
add_action('template_redirect', 'handle_login_form_submission');

// Display error message on the login page
function display_login_error_message() {
	if (isset($_SESSION['login_error'])) {
		echo '<p style="color:red;">' . $_SESSION['login_error'] . '</p>';
		unset($_SESSION['login_error']);  // Clear the error message after displaying it
	}
}
add_action('wp_footer', 'display_login_error_message');
