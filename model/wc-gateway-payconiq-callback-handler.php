<?php

namespace wc_payconiq\model;

use wc_payconiq\lib\Payconiq_Client;

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
		add_action( 'woocommerce_api_wc_gateway_payconiq', array( $this, 'check_response' ) );
		add_action( 'wp_ajax_nopriv_check_order_status', array( $this, 'check_order_status' ) );
		add_action( 'wp_ajax_check_order_status', array( $this, 'check_order_status' ) );

		$this->sandbox = $sandbox;
	}

	/**
	 * Process Payconiq callback Response.
	 */
	public function check_response() {
		$order_id = $_GET['webhookId'];

		$order = wc_get_order( $order_id );

		if ( $order == false ) {
			Wc_Gateway_Payconiq::log( $order_id . ' is not valid order.', 'error' );

			return;
		}

		/**
		 * @var \wc_payconiq\lib\Payconiq_Client
		 */
		$payconiq = ( ! $this->sandbox ) ? new Payconiq_Client( $this->get_option( 'api_merchant_id' ), $this->get_option( 'api_key' ) ) : new Payconiq_Client( $this->get_option( 'sandbox_api_merchant_id' ), $this->get_option( 'sandbox_api_key' ) );

		$transaction_id = get_post_meta( $order->get_id(), '_payconiq_transaction_id', true );

		try {
			$response = $payconiq->retrieveTransaction( $transaction_id, true );
		} catch ( \Exception $e ) {
			Wc_Gateway_Payconiq::log( 'Something went wrong with retrieving the transaction.', 'error' );

			return;
		}

		switch ( $response['status'] ) {
			case 'SUCCEEDED':
				$order->payment_complete();
				$order->add_order_note( 'The order is completed in Payconiq.' );
				Wc_Gateway_Payconiq::log( 'The order is completed in Payconiq' );
				break;
			case 'CREATION':
			case 'PENDING':
			case 'CONFIRMED':
				$order->update_status( 'pending' );
				$order->add_order_note( 'Order is pending due to payconiq order status: ' . $response['status'] );
				Wc_Gateway_Payconiq::log( 'Order is pending due to payconiq order status: ' . $response['status'] );
				break;
			case 'CANCELED':
			case 'CANCELED_BY_MERCHANT':
			case 'FAILED':
			case 'TIMEDOUT':
			case 'BLOCKED':
				$order->update_status( 'cancelled' );
				$order->add_order_note( 'Order is cancelled due to payconiq order status: ' . $response['status'] );
				Wc_Gateway_Payconiq::log( 'Order is pending due to payconiq order status: ' . $response['status'], 'error' );
				break;
		}

		/**
		 * Save order changes
		 */
		$order->save();
	}

	/**
	 * Ajax: Returns order status back to frontend
	 *
	 * @since 1.0.0
	 */
	public function check_order_status() {
		$order_id = ( isset( $_GET['order_id'] ) ) ? sanitize_text_field( $_GET['order_id'] ) : false;

		if( $order_id == false ) {
			die();
		}

		$order = wc_get_order($order_id);

		echo $order->get_status();
		die;
	}
}