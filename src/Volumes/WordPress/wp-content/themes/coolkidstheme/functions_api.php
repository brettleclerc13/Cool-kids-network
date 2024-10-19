<?php
/*** API ENDPOINTS ***/

// add_action('rest_api_init', function () {
//     register_rest_route('coolkids/v1', '/get-nonce', array(
//         'methods' => 'GET',
//         'callback' => 'generate_nonce_for_api',
//         'permission_callback' => '__return_true', // Allow anyone to access
//     ));
// });

// function generate_nonce_for_api() {
//     return wp_create_nonce('wp_rest');
// }

/*
	Register a custom API route for role updates
*/
function custom_register_api_endpoints() {
	register_rest_route('coolkids/v1', '/update-role/', array(
    	'methods' => WP_REST_Server::CREATABLE,
    	'callback' => 'update_user_role',
    	'permission_callback' => function () {
        // Check if the user is logged in and is an administrator
        return current_user_can('administrator'); 
   		},
	));
}
add_action('rest_api_init', 'custom_register_api_endpoints');

// Callback function to handle the role update
function update_user_role(WP_REST_Request $request) {
    $email = sanitize_email($request->get_param('email'));
    $first_name = sanitize_text_field($request->get_param('first_name'));
    $last_name = sanitize_text_field($request->get_param('last_name'));
    $new_role = sanitize_text_field($request->get_param('new_role'));

    // Check if the role is a cool role, otherwise send back an error using the WP_Error class 
    if (!in_array($new_role, array('cool_kid', 'cooler_kid', 'coolest_kid'))) {
        return new WP_Error('invalid_role', 'Invalid role provided', array('status' => 400));
    }

    // Try to find the user by email or first and last name
    $user = get_user_by('email', $email);
    if (!$user) {
        // If the email is not valid, check if the first and last names match
        $user_query = new WP_User_Query(array(
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => 'first_name',
                    'value' => $first_name,
                    'compare' => '='
                ),
                array(
                    'key' => 'last_name',
                    'value' => $last_name,
                    'compare' => '='
                )
            )
        ));
        $users = $user_query->get_results();
        if (empty($users)) {
            return new WP_Error('user_not_found', 'User not found', array('status' => 404));
        }
		// Consider first user for the role change
        $user = $users[0];
    }

    // Update user's role
    wp_update_user(array('ID' => $user->ID, 'role' => $new_role));

    return array('message' => 'User role updated successfully');
}
