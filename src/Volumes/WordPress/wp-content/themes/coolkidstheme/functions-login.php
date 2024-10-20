<?php
/*** LOGIN PAGE ***/

// Shortcode function for login form
function login_form_shortcode() {
	ob_start(); ?>
	<form method="POST" action="">
		<?php wp_nonce_field( 'login_action', 'login_nonce' ); ?>
		<label for="login_email">Email:</label>
		<input type="email" name="login_email" required>
		<label for="login_password">Password:</label>
		<input type="text" name="login_password" placeholder="password123" required>
		<input type="submit" name="login_submit" value="Log In">
	</form>
	<?php
	return ob_get_clean();
}
add_shortcode( 'login_form', 'login_form_shortcode' );

// Log in button action, logs in user if password is correct.
function handle_login_form_submission() {
	// Check if the login form is submitted
	if ( isset( $_POST['login_submit'] ) && isset( $_POST['login_email'] ) && isset( $_POST['login_password'] ) ) {
		// Verify nonce
		if ( isset( $_POST['login_nonce'] ) &&
			wp_verify_nonce( $_POST['login_nonce'], 'login_action' ) ) {

			$password = $_POST['login_password'];

			// Simple static password check
			if ( 'password123' !== $password ) {
				$_SESSION['login_error'] = 'Incorrect password, please type in "password123" as the password';
				wp_safe_redirect( home_url( '/login' ) );
				exit;
			}

			$email = sanitize_email( $_POST['login_email'] );
			// Check if the user exists based on the email
			$user = get_user_by( 'email', $email );
			if ( $user ) {
				// Log in the user
				wp_set_current_user( $user->ID );
				wp_set_auth_cookie( $user->ID );
				wp_safe_redirect( home_url( '/user-dashboard' ) );
				exit;
			} else {
				wp_safe_redirect( home_url( '/signup' ) );
				exit;
			}
		}
	}
}
add_action( 'template_redirect', 'handle_login_form_submission' );
