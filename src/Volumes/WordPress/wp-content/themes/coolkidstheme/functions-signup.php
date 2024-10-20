<?php
/*** SIGNUP PAGE ***/

// Shortcode function for signup form with email field
function signup_form_shortcode() {
	ob_start(); ?>
	<form method="POST" action="">
		<?php wp_nonce_field( 'signup_action', 'signup_nonce' ); ?>
		<label for="signup_email">Email:</label>
		<input type="email" name="signup_email" required>
		<input type="submit" name="signup_submit" value="Sign Up">
	</form>
	<?php
	return ob_get_clean();
}
add_shortcode( 'signup_form', 'signup_form_shortcode' );

function handle_signup_form_submission() {
	// Check if the form is submitted and the email field is set
	if ( isset( $_POST['signup_submit'] ) && isset( $_POST['signup_email'] ) ) {
		// Verify nonce
		if ( isset( $_POST['signup_nonce'] ) &&
			wp_verify_nonce( $_POST['signup_nonce'], 'signup_action' ) ) {

			$email = sanitize_email( $_POST['signup_email'] );

			// Check if the email is already registered, if the user exists or not
			$user = get_user_by( 'email', $email );
			if ( $user ) {
				if ( in_array( 'administrator', $user->roles, true ) ) {
					wp_safe_redirect( home_url( '/wp-admin' ) );
					return;
				}

				// Log in the non-admin user
				wp_set_current_user( $user->ID );
				wp_set_auth_cookie( $user->ID );
				wp_safe_redirect( home_url( '/user-dashboard' ) );
				return;
			}

			// Generate the new user's data using randomuser.me
			$api_url  = 'https://randomuser.me/api/';
			$response = wp_remote_get( $api_url );
			if ( is_wp_error( $response ) ) {
				wp_die( 'We encountered an issue while generating your character. Please try again later.' );
				return;
			}

			$body = wp_remote_retrieve_body( $response );
			$data = json_decode( $body );

			if ( $data ) {
				$first_name = $data->results[0]->name->first;
				$last_name  = $data->results[0]->name->last;
				$nick_name  = $data->results[0]->name->first;
				$country    = $data->results[0]->location->country;

				// Create a new user in WordPress with a static password
				$user_id = wp_create_user( $email, 'password123', $email );

				// Set user meta data
				update_user_meta( $user_id, 'first_name', $first_name );
				update_user_meta( $user_id, 'last_name', $last_name );
				update_user_meta( $user_id, 'nickname', $nick_name );
				update_user_meta( $user_id, 'country', $country );

				// Assign the Cool Kid role by default
				wp_update_user(
					array(
						'ID'   => $user_id,
						'role' => 'cool_kid',
					)
				);

				// Log in the new user
				wp_set_current_user( $user_id );
				wp_set_auth_cookie( $user_id );

				// Redirect the user to the user-dashboard after signup
				wp_safe_redirect( home_url( '/user-dashboard' ) );
				exit;
			}
		}
	}
}
add_action( 'template_redirect', 'handle_signup_form_submission' );
