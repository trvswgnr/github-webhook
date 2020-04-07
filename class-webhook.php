<?php
/**
 * GitHub Webhook
 *
 * @package ghwh
 */

/** Webhook */
class Webhook {
	public $payload;
	public $event;
	public $merged = false;
	/**
	 * Construct
	 *
	 * @param string $shared_secret (optional) GitHub shared secret.
	 */
	public function __construct( $shared_secret = false ) {
		$this->get_event();
		// validate secret if one is provided
		if ( $shared_secret ) {
			$this->validate_secret( $shared_secret );
		}
		$this->payload = isset( $_POST['payload'] ) ? json_decode( $_POST['payload'] ) : false;
		switch ( $this->event ) {
			case 'pull_request':
				$this->branch = $this->payload->pull_request->base->ref;
				$this->merged = $this->payload->pull_request->merged;
				break;

			default:
				$this->branch = substr( strrchr( $this->payload->ref, 'heads/' ), 6 );
				break;
		}
		$this->deploy( 'test' );
	}

	/** Get GitHub event */
	public function get_event() {
		$this->event = isset( $_SERVER['HTTP_X_GITHUB_EVENT'] ) ? filter_var( $_SERVER['HTTP_X_GITHUB_EVENT'], FILTER_SANITIZE_STRING ) : false;
		if ( false === $this->event ) {
			die( 'GitHub event not found.' );
		}
	}

	/**
	 * Validate secret
	 *
	 * @param string $shared_secret GitHub shared secret.
	 */
	public function validate_secret( $shared_secret ) {
		$github_signature = isset( $_SERVER['HTTP_X_HUB_SIGNATURE'] ) ? explode( '=', filter_var( $_SERVER['HTTP_X_HUB_SIGNATURE'], FILTER_SANITIZE_STRING ) )[1] : false;
		$known_signature  = hash_hmac( 'sha1', file_get_contents( 'php://input' ), $shared_secret, false );
		if ( ! $github_signature || ! hash_equals( $known_signature, $github_signature ) ) {
			die( 'Secret not valid.' );
		}
		return 'Secret validated';
	}

	/**
	 * Deploy from GitHub event
	 *
	 * @param string $trigger (optional) Event to trigger action.
	 * @param string $branch (optional) Branch that action occurs on.
	 * @param string $deploy_path Path on server to deploy to. Should be a Git repository with GitHub remote.
	 */
	public function deploy( $deploy_path, $trigger = 'pull_request', $branch = 'master', $merged = true ) {
		if (
			$trigger !== $this->event ||
			$branch !== $this->branch ||
			$merged !== $this->merged ||
			! $deploy_path
		) {
			echo "Wrong action, not on correct branch '$branch', and/or not merged. Skipping...";
			return false;
		}
		echo "Trigger event '$trigger' detected. On correct branch '$branch' & is merged = $merged. Deploying...";
		$this->create_deploy_script();
		// $old_path = getcwd();
		// chdir( __DIR__ );
		// $output = shell_exec( './deploy.sh' );
		// chdir( $old_path );
		return true;
	}

	/** Create deploy.sh bash file */
	public function create_deploy_script() {
		if ( file_exists( 'deploy.sh' ) ) {
			echo 'deploy script exists';
		}
	}
}
