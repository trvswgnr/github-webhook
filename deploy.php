<?php
var_dump( $_SERVER );

$shared_secret = 'notverysecret';
$secret        = hash_hmac( 'sha1', $_POST['payload'], $shared_secret, false );
echo $secret;

// try {
// $handler = new Handler( 'notverysecret', __DIR__ );
// if ( $handler->handle() ) {
// echo 'OK';
// } else {
// echo 'Wrong secret';
// }
// } catch ( Exception $e ) {
// echo 'Caught exception: ',  $e->getMessage(), "\n";
// }


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
