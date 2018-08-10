<?php

namespace wc_payconiq\model;

class Wc_Gateway_Payconiq extends \WC_Payment_Gateway {

	CONST ID = 'Wc_Gateway_Payconiq';

	/**
	 * Whether or not logging is enabled
	 *
	 * @var bool
	 */
	public static $log_enabled = false;

	/**
	 * Logger instance
	 *
	 * @var \WC_Logger
	 */
	public static $log = false;

	/**
	 * Constructor for the gateway.
	 */
	public function __construct() {
		$this->id                 = 'payconiq';
		$this->has_fields         = true;
		$this->order_button_text  = __( 'Proceed to Payconiq', 'woocommerce' );
		$this->method_title       = __( 'Payconiq', 'woocommerce' );
		$this->method_description = __( 'Take payments through Payconiq app.', 'woocommerce' );
		$this->supports           = array(
			'products',
			'refunds',
		);

		// Load the settings.
		$this->init_form_fields();
		$this->init_settings();

		// Define user set variables.
		$this->title          = $this->get_option( 'title' );
		$this->description    = $this->get_option( 'description' );
		$this->testmode       = 'yes' === $this->get_option( 'testmode', 'no' );
		$this->debug          = 'yes' === $this->get_option( 'debug', 'no' );
		$this->email          = $this->get_option( 'email' );
		$this->receiver_email = $this->get_option( 'receiver_email', $this->email );
		$this->identity_token = $this->get_option( 'identity_token' );
		self::$log_enabled    = $this->debug;

		if ( $this->testmode ) {
			/* translators: %s: Link to PayPal sandbox testing guide page */
			$this->description .= ' ' . __( 'SANDBOX ENABLED. You can use sandbox testing accounts only.', 'woocommerce' );
			$this->description = trim( $this->description );
		}

		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array(
			$this,
			'process_admin_options'
		) );
	}

	/**
	 * Initialise Gateway Settings Form Fields.
	 */
	public function init_form_fields() {
		$this->form_fields = include WC_PAYCONIQ_DIR . 'view/admin/settings-payconiq.php';
	}

	/**
	 * Processes and saves options.
	 * If there is an error thrown, will continue to save and validate fields, but will leave the erroring field out.
	 *
	 * @return bool was anything saved?
	 */
	public function process_admin_options() {
		$saved = parent::process_admin_options();

		// Maybe clear logs.
		if ( 'yes' !== $this->get_option( 'debug', 'no' ) ) {
			if ( empty( self::$log ) ) {
				self::$log = wc_get_logger();
			}
			self::$log->clear( 'paypal' );
		}

		return $saved;
	}

	/**
	 * Logging method.
	 *
	 * @param string $message Log message.
	 * @param string $level Optional. Default 'info'. Possible values:
	 *                      emergency|alert|critical|error|warning|notice|info|debug.
	 */
	public static function log( $message, $level = 'info' ) {
		if ( self::$log_enabled ) {
			if ( empty( self::$log ) ) {
				self::$log = wc_get_logger();
			}
			self::$log->log( $level, $message, array( 'source' => 'payconiq' ) );
		}
	}

	public function process_payment( $order_id ) {
		global $woocommerce;
		$order = new \WC_Order( $order_id );

		// mark as payment complete
		$order->payment_complete();

		// Reduce stock levels
		wc_reduce_stock_levels( $order_id );

		// Remove cart
		$woocommerce->cart->empty_cart();

		// Return thankyou redirect
		return array(
			'result'   => 'success',
			'redirect' => $this->get_return_url( $order )
		);
	}

	public function payment_fields() {
		$description = $this->get_description();

		$this->credit_card_form();
		if ( $description ) {
			echo wpautop( wptexturize( $description ) ); // @codingStandardsIgnoreLine.
		}

		if ( $this->supports( 'default_credit_card_form' ) ) {
			$this->credit_card_form(); // Deprecated, will be removed in a future version.
		}
	}
}