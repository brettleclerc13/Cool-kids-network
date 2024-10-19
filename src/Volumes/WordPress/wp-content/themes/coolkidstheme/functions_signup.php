<?php

// SIGNUP PAGE
global $signup_message;

// Confirm button action, signs user up with retrieved random data
function handle_signup_form_submission() {
	global $signup_message;
	/*
		Since I'm using WP forms, I need to target the field name which is ugly
		(for the email field at least)
	*/
	if (isset($_POST['wpforms']['submit']) && isset($_POST['wpforms']['fields'][2])) {
        $email = sanitize_email($_POST['wpforms']['fields'][2]);
        
		// Checking if the email is already registered
        $user = get_user_by('email', $email); // Get user object by email

        if ($user) {
			// User already exists: set the login message
			$signup_message = '<p>You already have an account, logging you in...</p>';

			// Log in the user simultaneously
            wp_set_current_user($user->ID);
            wp_set_auth_cookie($user->ID);
            wp_redirect(home_url('/user-dashboard'));
            exit;
        }

        // Generating the user's data using randomuser.me
        $api_url = 'https://randomuser.me/api/';
        $response = wp_remote_get($api_url);
        if (is_wp_error($response)) {
            error_log('Error fetching character data from randomuser.me: ' . $response->get_error_message()); // Logging to debug.log
    
    		wp_die('We encountered an issue while generating your character. Please try again later.');
			return ;
        }

		// Converting data into an array for the next step
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body);

        if ($data) {
            $first_name = $data->results[0]->name->first;
            $last_name = $data->results[0]->name->last;
			$nick_name = $data->results[0]->name->first;
            $country = $data->results[0]->location->country;

            // Creating a new user in WordPress with a static password
            $user_id = wp_create_user($email, 'password123', $email);

            // Set user meta data
            update_user_meta($user_id, 'first_name', $first_name);
            update_user_meta($user_id, 'last_name', $last_name);
			update_user_meta($user_id, 'nickname', $nick_name);
            update_user_meta($user_id, 'country', $country);

            // Assigning the Cool Kid role with wp_update_user
            wp_update_user([
                'ID' => $user_id,
                'role' => 'cool_kid',
            ]);
			// Log in the new user
			wp_set_current_user($user_id); // Set the current user
			wp_set_auth_cookie($user_id); // Set the authentication cookie
			
			// User registered: Set the signup message
			$signup_message = '<p>Registration successful, creating your new character...</p>';
			//Redirect to dashboard after signup
			wp_redirect(home_url('/user-dashboard'));
			exit;
        }
    }
}
add_action('template_redirect', 'handle_signup_form_submission');

// Output message after signup form button
function display_signup_message() {
    global $signup_message;
    if (!empty($signup_message)) {
        echo '<script>document.querySelector(".signup-button-form").insertAdjacentHTML("afterend", `' . $signup_message . '`);</script>';
    }
}
add_action('wp_footer', 'display_signup_message');
