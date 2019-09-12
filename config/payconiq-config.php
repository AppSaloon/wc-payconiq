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
		add_action( 'wp_loaded', array( $this, 'cancel_order_cart' ), 20 );
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
			'message' => '',
		);

		$order_id = ( isset( $_POST['order_id'] ) ) ? sanitize_text_field( $_POST['order_id'] ) : false;

		if ( $order_id == false ) {
			die;
		}

		$order = wc_get_order( $order_id );

		$response['status'] = $order->get_status();

		switch ( $order->get_status() ) {
			case 'completed':
			case 'processing':
				$response['message'] = $order->get_checkout_order_received_url();
				break;
			case 'cancelled':
				$response['message'] = $this->get_cancel_url($order);
				break;
		}

		wp_send_json( $response, 200 );
		die;
	}

	/**
	 * Returns order cancel url
	 *
	 * @param $order    \WC_Order   WC Order Object
	 *
	 * @return string   string  Callback URL to the cart
	 *
	 * @since 1.0.3
	 */
	public function get_cancel_url($order) {
		return add_query_arg(
			array(
				'cancel_order' => 'true',
				'order'        => $order->get_order_key(),
				'order_id'     => $order->get_id(),
				'redirect'     => false,
				'_wpnonce'     => wp_create_nonce( 'woocommerce-cancel_order-payconiq' ),
			),
			$order->get_cancel_endpoint()
		);
	}

	/**
	 * The callback to the cart when the payconiq payment is cancelled
	 *
	 * @since 1.0.3
	 */
	public function cancel_order_cart() {
		if (
			isset( $_GET['cancel_order'] ) &&
			isset( $_GET['order'] ) &&
			isset( $_GET['order_id'] ) &&
			( isset( $_GET['_wpnonce'] ) && wp_verify_nonce( wp_unslash( $_GET['_wpnonce'] ), 'woocommerce-cancel_order-payconiq' ) ) // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		) {
			wc_nocache_headers();

			$order_key        = wp_unslash( $_GET['order'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			$order_id         = absint( $_GET['order_id'] );
			$order            = wc_get_order( $order_id );
			$user_can_cancel  = current_user_can( 'cancel_order', $order_id );
			$order_can_cancel = $order->has_status( apply_filters( 'woocommerce_valid_order_statuses_for_cancel', array( 'pending', 'failed', 'cancelled' ), $order ) );

			if ( $user_can_cancel && $order_can_cancel && $order->get_id() === $order_id && hash_equals( $order->get_order_key(), $order_key ) ) {

				// Cancel the order + restore stock.
				WC()->session->set( 'order_awaiting_payment', false );

				wc_add_notice( apply_filters( 'woocommerce_order_cancelled_notice', __( 'Your order was cancelled.', 'woocommerce' ) ), apply_filters( 'woocommerce_order_cancelled_notice_type', 'notice' ) );

				do_action( 'woocommerce_cancelled_order', $order->get_id() );

			} elseif ( $user_can_cancel && ! $order_can_cancel ) {
				wc_add_notice( __( 'Your order can no longer be cancelled. Please contact us if you need assistance.', 'woocommerce' ), 'error' );
			} else {
				wc_add_notice( __( 'Invalid order.', 'woocommerce' ), 'error' );
			}
		}
	}
}