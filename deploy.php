<?php
require_once 'class-github-webhook.php';
$webhook = new GitHub_WebHook();

if ( $webhook->ValidateHubSignature( 'notverysecret' ) ) {
	echo 'validated';
} else {
	echo 'not validated';
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
