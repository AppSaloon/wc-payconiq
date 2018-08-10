<?php

namespace wc_payconiq\config;

class Plugin_Deactivate {

	/**
	 * Runs when the plugin is disabled.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		//register_deactivation_hook( WC_PAYCONIQ_FILE, array( $this, 'deactive' ) );
	}

	/**
	 * Actions to do after deactivating the plugin
	 *
	 * @since 1.0.0
	 */
	public function deactive() {
		// do nothing
	}
}