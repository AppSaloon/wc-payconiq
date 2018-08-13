<?php

namespace wc_payconiq\config;

use wc_payconiq\lib\Container;
use wc_payconiq\model\Wc_Gateway_Payconiq;

class Payconiq_Config {

	/**
	 * Init_Config constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_filter( 'woocommerce_payment_gateways', array( $this, 'add_payconiq_gateway_class' ) );
	}

	public function add_payconiq_gateway_class( $methods ) {
		$methods[] = 'wc_payconiq\model\Wc_Gateway_Payconiq';
		return $methods;
	}
}