<?php

namespace wc_payconiq\config;

use wc_payconiq\lib\Container;

class Plugin_Activate {

	/**
	 * Runs when the plugin is activated
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		//register_activation_hook( WC_PAYCONIQ_FILE, array( $this, 'activation' ) );
	}

	/**
	 * Actions to do when the plugin is activated
	 *
	 * @since 1.0.0
	 */
	public function activation() {
		// do nothing
	}
}