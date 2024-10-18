<?php

// GENERAL FUNCTIONS
// Adding 20 24 parent theme styles + cool kids theme styles
function coolkids_enqueue_styles() {
    // Enqueuing the parent theme style
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    
    // Enqueuing the child theme style
    wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/style.css', ['parent-style']);
}
add_action('wp_enqueue_scripts', 'coolkids_enqueue_styles');

add_action('wp_footer', 'custom_button_action');

// Installing Cool Kids' roles
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

// HOMEPAGE FUNCTIONS
/*
	Button actions:
	Signup navbar button -> signup page
	Login navbar button -> login page
	Get started button -> signup page
*/
function custom_button_action() {
    ?>
    <script>
        document.getElementById('signup-button-header').addEventListener('click', function() {
            window.location.href = '/signup';
        });
    </script>
    <?php
}

// SIGNUP PAGE
// Confirm button action, sign user up with retrieved random data
function handle_signup_form_submission() {
	/*
		Since I'm using WP forms, I need to target the field name which is ugly
		(for email field at least)
	*/
	if (isset($_POST['wpforms']['submit']) && isset($_POST['wpforms']['fields'][2])) {
        $email = sanitize_email($_POST['wpforms']['fields'][2]);
        
		// Checking if the email is already registered
        $user = get_user_by('email', $email); // Get user object by email

        if ($user) {
            // Email exists; log in the user
            wp_set_current_user($user->ID); // Set the current user
            wp_set_auth_cookie($user->ID); // Set the authentication cookie
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

			// Redirecting to dashboard after signup
			wp_redirect(home_url('/user-dashboard'));
			exit;
        }
    }
}
add_action('template_redirect', 'handle_signup_form_submission');

// USER DASHBOARD

function display_user_character() {
	$current_user = wp_get_current_user();
    $output = '<ul>';

	$email = get_user_meta($current_user->user_email, 'email', true);
	$roles = implode(', ', $current_user->roles);
	$first_name = get_user_meta($current_user->ID, 'first_name', true);
    $last_name = get_user_meta($current_user->ID, 'last_name', true);
    $country = get_user_meta($current_user->ID, 'country', true);

	$output .= "<li>{$first_name} {$last_name} from {$country}, Email: {$email}, Role: {$roles}</li>";
	$output .= '</ul>';
	return $output;
}
add_shortcode('current_user_character', 'display_user_character');

/*
	Shortcode to check the user's role and display user data accordingly.
*/
function display_other_user_characters() {
    $current_user = wp_get_current_user();
    $output = '';

    /*
		If user is only a cool kid, output a sorry message and return.
		Else, retrieve all users and parse details as per other roles.
	*/
    if (in_array('Cool Kid', $current_user->roles)) {
        $output .= '<p>You need to be cooler to view other chracters. Sorry!</p>';
    } else {
        // Fetch all users
        $users = get_users();
        $output .= '<ul>';

        foreach ($users as $user) {
            // Skipping the current user from the list
            if ($user->ID === $current_user->ID)
				continue;

            $first_name = get_user_meta($user->ID, 'first_name', true);
            $last_name = get_user_meta($user->ID, 'last_name', true);
            $country = get_user_meta($user->ID, 'country', true);

            // For cooler kid users, showing names and countries
            if (in_array('Cooler Kid', $current_user->roles)) {
                $output .= "<li>{$first_name} {$last_name} from {$country}</li>";
            }

            // For coolest kid users: Showing emails and roles as well
            if (in_array('Coolest Kid', $current_user->roles)) {
                $email = $user->user_email;
                $roles = implode(', ', $user->roles);
                $output .= "<li>{$first_name} {$last_name} from {$country}, Email: {$email}, Role: {$roles}</li>";
            }
        }
        $output .= '</ul>';
    }
    return $output;
}
add_shortcode('other_user_characters', 'display_other_user_characters');
