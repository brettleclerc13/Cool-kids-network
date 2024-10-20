<?php

// Get user input
function get_input( $prompt ) {
	echo $prompt;
	return trim( fgets( STDIN ) );
}

// Convert username and password to Base64 for authorization
function base64_encode_credentials( $username, $password ) {
	// Encode the username and password in Base64 for transmission in the Authorization header
	return base64_encode( "$username:$password" );
}

// Ask for the user's email
$email = get_input( "A custom curl command creator\nPlease enter the email of the user whose role you wish to change\n>> " );

// Ask for the new role to assign
$user_role = get_input( "Which role do you wish to assign the user with? (cool_kid, cooler_kid, coolest_kid)\n>> " );

// Ask for optional first and last name
$insert_names = get_input( "Do you want to provide us with their first and last name as well? (y/n)\n>> " );

$first_name = '';
$last_name  = '';

// Ask for the first and last names if user said yes
if ( strtolower( $insert_names ) === 'y' ) {
	$first_name = get_input( "First name:\n>> " );
	$last_name  = get_input( "Last name:\n>> " );
}

// Ask for admin username and password for authentication
$admin_username = get_input( "Please enter your admin username\n>> " );

// Convert admin username and password to Base64
$base64_credentials = base64_encode_credentials( $admin_username, 'JUnS NlzU WVYu Ug6l JSS3 9Mz7' );

$data = array(
	array(
		'email'    => $email,
		'new_role' => $user_role,
	)
);

// Add first and last names if provided
if ( ! empty( $first_name ) && ! empty( $last_name ) ) {
	$data['first_name'] = $first_name;
	$data['last_name']  = $last_name;
}

// Convert the data to JSON format
$json_data = json_encode( $data );

// Inform the user about the API request
echo "Sending API request...\n";

// Run the curl command
$curl = curl_init();
curl_setopt_array($curl,
	array(
		CURLOPT_URL => 'http://localhost:8080/wp-json/coolkids/v1/update-role',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_CUSTOMREQUEST  => 'POST',
		CURLOPT_HTTPHEADER     => array(
			'Authorization: Basic ' . $base64_credentials,
			'Content-Type: application/json',
		),
		CURLOPT_POSTFIELDS     => $json_data,
	)
);

$response = curl_exec( $curl );

// Check for curl errors
if ( false === $response ) {
	$error = curl_error( $curl );
	curl_close( $curl );
	die( "Curl error: $error\n" );
}

curl_close( $curl );

// Display the response from the API
echo "Response:\n$response\n";
