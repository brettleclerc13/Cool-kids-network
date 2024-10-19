<?php
/*** SIGNUP PAGE ***/

// Confirm button action, signs user up with retrieved random data
function handle_signup_form_submission() {
	/*
		Since I'm using WP forms, I need to target the wpforms IDs which is ugly
	*/
	// Check if the correct WP Form is used (signup form)
	if (isset($_POST['wpforms']['id']) && $_POST['wpforms']['id'] == 64) {
		// Check if the form is submitted and the email field is set
		if (isset($_POST['wpforms']['submit']) && isset($_POST['wpforms']['fields'][2])) {
			$email = sanitize_email($_POST['wpforms']['fields'][2]);
			
			// Check if the email is already registered, if the user exists or not
			$user = get_user_by('email', $email);
			if ($user) {
				/*
					Check if user is admin or not.
					Added security measure since the admin user(s) have access to the WP CMS DB.
					And the signup form can login the admin user with only an email.
				*/
				if (in_array('administrator', $user->roles)) {
					wp_redirect(home_url('/wp-admin'));
					return ;
				}
	
				// Log in the non-admin user
				wp_set_current_user($user->ID);
				wp_set_auth_cookie($user->ID);
				wp_redirect(home_url('/user-dashboard'));
				return ;
			}
	
			// Generate the new user's data using randomuser.me
			$api_url = 'https://randomuser.me/api/';
			$response = wp_remote_get($api_url);
			if (is_wp_error($response)) {
				error_log('Error fetching character data from randomuser.me: ' . $response->get_error_message()); // Logging to debug.log
		
				wp_die('We encountered an issue while generating your character. Please try again later.');
				return ;
			}
	
			// Convert data into an array for the next step
			$body = wp_remote_retrieve_body($response);
			$data = json_decode($body);
	
			if ($data) {
				$first_name = $data->results[0]->name->first;
				$last_name = $data->results[0]->name->last;
				$nick_name = $data->results[0]->name->first;
				$country = $data->results[0]->location->country;
	
				// Create a new user in WordPress with a static password
				$user_id = wp_create_user($email, 'password123', $email);
	
				// Set user meta data
				update_user_meta($user_id, 'first_name', $first_name);
				update_user_meta($user_id, 'last_name', $last_name);
				update_user_meta($user_id, 'nickname', $nick_name);
				update_user_meta($user_id, 'country', $country);
	
				// Assign the Cool Kid role by default
				wp_update_user([
					'ID' => $user_id,
					'role' => 'cool_kid',
				]);
				// Log in the new user
				wp_set_current_user($user_id);
				wp_set_auth_cookie($user_id);
	
				//Redirect the user to the user-dashboard after signup
				wp_redirect(home_url('/user-dashboard'));
			}
		}
	}
}
add_action('template_redirect', 'handle_signup_form_submission');
