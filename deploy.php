<?php
/**
 * Deploy from GitHub Merge/Push
 *
 * @package ghwh
 */

require_once 'config.php';
require_once 'class-webhook.php';

$webhook = new Webhook( SHARED_SECRET );
