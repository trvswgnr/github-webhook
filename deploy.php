<?php
// where to log errors and successful requests
define( 'LOGFILE', '/tmp/github-webhook.log' );

// what command to execute upon retrieval of a valid push event
$cmd = 'update-jekyll.sh 2>&1';

// the shared secret, used to sign the POST data (using HMAC with SHA1)
$secret = '00000000000000000000000000000000';

// receive POST data for signature calculation, don't change!
$post_data = file_get_contents( 'php://input' );
$signature = hash_hmac( 'sha1', $post_data, $secret );

// required data in POST body - set your targeted branch and repository here!
$required_data = array(
	'ref'        => 'refs/heads/master',
	'repository' => array(
		'full_name' => 'owner/repo',
	),
);

// required data in headers - probably doesn't need changing
$required_headers = array(
	'REQUEST_METHOD'       => 'POST',
	'HTTP_X_GITHUB_EVENT'  => 'push',
	'HTTP_USER_AGENT'      => 'GitHub-Hookshot/*',
	'HTTP_X_HUB_SIGNATURE' => 'sha1=' . $signature,
);

// END OF CONFIGURATION

error_reporting( 0 );

function log_msg( $msg ) {
	if ( LOGFILE != '' ) {
		file_put_contents( LOGFILE, $msg . "\n", FILE_APPEND );
	}
}

function array_matches( $have, $should, $name = 'array' ) {
	$ret = true;
	if ( is_array( $have ) ) {
		foreach ( $should as $key => $value ) {
			if ( ! array_key_exists( $key, $have ) ) {
				log_msg( "Missing: $key" );
				$ret = false;
			} elseif ( is_array( $value ) && is_array( $have[ $key ] ) ) {
				$ret &= array_matches( $have[ $key ], $value );
			} elseif ( is_array( $value ) || is_array( $have[ $key ] ) ) {
				log_msg( "Type mismatch: $key" );
				$ret = false;
			} elseif ( ! fnmatch( $value, $have[ $key ] ) ) {
				log_msg( "Failed comparison: $key={$have[$key]} (expected $value)" );
				$ret = false;
			}
		}
	} else {
		log_msg( "Not an array: $name" );
		$ret = false;
	}
	return $ret;
}

log_msg( "=== Received request from {$_SERVER['REMOTE_ADDR']} ===" );
header( 'Content-Type: text/plain' );
$data = json_decode( $post_data, true );
// First do all checks and then report back in order to avoid timing attacks
$headers_ok = array_matches( $_SERVER, $required_headers, '$_SERVER' );
$data_ok    = array_matches( $data, $required_data, '$data' );
if ( $headers_ok && $data_ok ) {
	passthru( $cmd );
} else {
	http_response_code( 403 );
	die( "Forbidden\n" );
}

// $payload   = json_decode( $_POST['payload'] );
// $on_master = 'master' === $payload->pull_request->base->ref ? true : false;
// $is_merged = $payload->pull_request->merged;
// if ( $on_master && $is_merged ) {
// echo 'on master & is merged';
// $old_path = getcwd();
// chdir( '/home/travisaw/webhooks/login-links' );
// $output = shell_exec( './deploy.sh' );
// chdir( $old_path );
// file_put_contents( 'll-github.txt', print_r( $_POST['payload'] . "\n\n", true ), FILE_APPEND | LOCK_EX );
// } else {
// echo 'not on master or not merge';
// }
