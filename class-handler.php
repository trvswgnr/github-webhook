<?php
/**
 * GitHub Webhook
 *
 * @package ghwh
 */

class Webhook {
	public function __construct( $secret ) {
		$this->validate_secret( $secret );
	}
	public function validate_secret( $secret ) {

	}
}
