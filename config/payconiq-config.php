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
		add_action( 'wp_ajax_payconiq_check_order_status', array( $this, 'check_order_status' ) );
		add_action( 'wp_ajax_nopriv_payconiq_check_order_status', array( $this, 'check_order_status' ) );
	}

	/**
	 * @param $methods
	 *
	 * @return array
	 *
	 * @since 1.0.0
	 */
	public function add_payconiq_gateway_class( $methods ) {
		$methods[] = 'wc_payconiq\model\Wc_Gateway_Payconiq';

		return $methods;
	}

	/**
	 * Ajax: Returns order status back to frontend
	 *
	 * @since 1.0.0
	 */
	public function check_order_status() {
		$response = array(
			'status'  => apply_filters( 'woocommerce_default_order_status', 'pending' ),
			'message' => ''
		);

		$order_id = ( isset( $_POST['order_id'] ) ) ? sanitize_text_field( $_POST['order_id'] ) : false;

		if ( $order_id == false ) {
			die;
		}

		$order = wc_get_order( $order_id );

		$response['status'] = $order->get_status();

		if ( $order->get_status() == 'completed' || $order->get_status == 'processing' ) {
			$response['message'] = $order->get_checkout_order_received_url();
		} else {
			//$response['message'] = __( 'Open the Payconiq app and scan the QR code.', 'wc-payconiq' );
		}

		wp_send_json( $response, 200 );
		die;
	}
}