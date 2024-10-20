<?php
/*** USER DASHBOARD ***/

// Restrict user-dashboard access to logged in users only
function restrict_user_dashboard_access() {
	// Check if the current page is the user dashboard and if the user is not logged in
	if ( is_page( 'user-dashboard' ) && ! is_user_logged_in() ) {
		// Redirect to signup page if the user is not logged in
		wp_safe_redirect( home_url( '/signup' ) );
		exit;
	}
}
add_action( 'template_redirect', 'restrict_user_dashboard_access' );

// Display user's character
function display_user_character() {
	$current_user = wp_get_current_user();
	$email        = $current_user->user_email;
	$roles        = implode( ', ', $current_user->roles );
	$first_name   = get_user_meta( $current_user->ID, 'first_name', true );
	$last_name    = get_user_meta( $current_user->ID, 'last_name', true );
	$country      = get_user_meta( $current_user->ID, 'country', true );
	$output       = '<p class="user-char">';
	$output      .= "{$first_name} {$last_name} from {$country}</p>";
	$output      .= '<p class="user-char">';
	$output      .= "Email: {$email}, Role: {$roles}</p>";
	return $output;
}
add_shortcode( 'current_user_character', 'display_user_character' );


// Shortcode to check the user's role and display the character details accordingly.
function display_other_user_characters() {
	$current_user = wp_get_current_user();
	$output       = '';
	/*
		If user is only a cool kid, output a sorry message and return.
		Else, retrieve all users and parse details as per other roles.
	*/
	if ( in_array( 'cool_kid', $current_user->roles, true ) ) {
		$output .= '<p class="user-char">You need to be cooler to view other chracters. Sorry!</p>';
	} else {
		// Fetch all users
		$users   = get_users();
		$output .= '<ul class="user-char-list">';

		foreach ( $users as $user ) {
			// Skipping the current user and administrator from the list
			if ( $user->ID === $current_user->ID || in_array( 'administrator', $user->roles, true ) ) {
				continue;
			}

			$first_name = get_user_meta( $user->ID, 'first_name', true );
			$last_name  = get_user_meta( $user->ID, 'last_name', true );
			$country    = get_user_meta( $user->ID, 'country', true );

			// For cooler kid users, showing names and countries
			if ( in_array( 'cooler_kid', $current_user->roles, true ) ) {
				$output .= "<li>{$first_name} {$last_name} from {$country}</li>";
			}

			// For coolest kid users: Showing emails and roles as well
			if ( in_array( 'coolest_kid', $current_user->roles, true ) ) {
				$email   = $user->user_email;
				$roles   = implode( ', ', $user->roles );
				$output .= "<li>{$first_name} {$last_name} from {$country}. Email: {$email}, Role: {$roles}</li>";
			}
		}
		$output .= '</ul>';
	}
	return $output;
}
add_shortcode( 'other_user_characters', 'display_other_user_characters' );
