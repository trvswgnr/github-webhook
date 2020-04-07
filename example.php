<?php
/**
 * Deploy from GitHub Merge/Push
 *
 * @package ghwh
 */

// get the class.
require_once 'class-webhook.php';

// get shared secret from global set in config.php.
$webhook = new Webhook( SHARED_SECRET );

// deploy if pull request is merged into master branch.
$webhook->deploy( '/path/to/directory' );
