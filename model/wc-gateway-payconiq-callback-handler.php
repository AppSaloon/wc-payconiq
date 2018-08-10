<?php

namespace wc_payconiq\model;

/**
 * WC_Gateway_Paypal_IPN_Handler class.
 */
class Wc_Gateway_Payconiq_Callback_Handler extends WC_Gateway_Payconiq_Response {

	/**
	 * Constructor.
	 *
	 * @param bool $sandbox Use sandbox or not.
	 * @param string $receiver_email Email to receive IPN from.
	 */
	public function __construct( $sandbox = false, $receiver_email = '' ) {
		add_action( 'woocommerce_api_wc_gateway_paypal', array( $this, 'check_response' ) );
		add_action( 'valid-payconiq-standard-callback-request', array( $this, 'valid_response' ) );

		$this->sandbox = $sandbox;
	}

	/**
	 * Check for Payconiq callback Response.
	 */
	public function check_response() {
		if ( ! empty( $_POST ) ) { // WPCS: CSRF ok.
			$posted = wp_unslash( $_POST ); // WPCS: CSRF ok, input var ok.

			// @codingStandardsIgnoreStart
			do_action( 'valid-payconiq-standard-callback-request', $posted );
			// @codingStandardsIgnoreEnd
			exit;
		}

		wp_die( 'PayPal IPN Request Failure', 'PayPal IPN', array( 'response' => 500 ) );
	}

	/**
	 * There was a valid response.
	 *
	 * @param  array $posted Post data after wp_unslash.
	 */
	public function valid_response( $posted ) {
		$order = ! empty( $posted['custom'] ) ? $this->get_payconiq_order( $posted['custom'] ) : false;

		if ( $order ) {

			// Lowercase returned variables.
			$posted['payment_status'] = strtolower( $posted['payment_status'] );

			Wc_Gateway_Payconiq::log( 'Found order #' . $order->get_id() );
			Wc_Gateway_Payconiq::log( 'Payment status: ' . $posted['payment_status'] );

			if ( method_exists( $this, 'payment_status_' . $posted['payment_status'] ) ) {
				call_user_func( array( $this, 'payment_status_' . $posted['payment_status'] ), $order, $posted );
			}
		}
	}

	protected function get_payconiq_order( $transaction_id ) {

	}
}