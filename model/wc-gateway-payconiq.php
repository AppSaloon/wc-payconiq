<?php

namespace wc_payconiq\model;

use chillerlan\QRCode\QRCode;
use wc_payconiq\lib\Payconiq_Client;

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
		$this->has_fields         = false;
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

		/**
		 * Callback handler
		 */
		new Wc_Gateway_Payconiq_Callback_Handler( $this->testmode );

		add_action( 'woocommerce_receipt_' . $this->id, array( $this, 'render_receipt_page' ) );

		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array(
			$this,
			'process_admin_options'
		) );

		add_action( 'woocommerce_admin_order_data_after_billing_address', array(
			$this,
			'show_transaction_id_in_backend'
		), 10, 1 );
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
		$order = wc_get_order( $order_id );

		return array(
			'result'   => 'success',
			'redirect' => $order->get_checkout_payment_url( true ),
		);
	}

	/**
	 * Renders the receipt page.
	 *
	 * @param int $order_id WooCommerce Order ID.
	 */
	public function render_receipt_page( $order_id ) {
		$order = wc_get_order( $order_id );

		/**
		 * If currency is not EUR
		 */
		if ( $order->get_currency() != 'EUR' ) {
			wp_die( 'payconiq works only with EUR ', array(
				'response',
				403
			) );
			exit;
		}

		/**
		 * Create callback url
		 */
		$callbackUrl = str_replace( 'http://', 'https://', add_query_arg( 'wc-api', 'Wc_Gateway_Payconiq', home_url( '/' ) ) );

		/**
		 * Add order ID to callback url
		 */
		$callbackUrl = add_query_arg( 'webhookId', $order_id, $callbackUrl );

		/**
		 * @var \wc_payconiq\lib\Payconiq_Client
		 */
		$payconiq = ( ! $this->testmode ) ? new Payconiq_Client( $this->get_option( 'api_merchant_id' ), $this->get_option( 'api_key' ) ) : new Payconiq_Client( $this->get_option( 'sandbox_api_merchant_id' ), $this->get_option( 'sandbox_api_key' ) );

		/**
		 * Create Transaction ID
		 */
		try {
			$transaction = $payconiq->createTransaction( $order->get_total() * 100, $order->get_currency(), $callbackUrl, true );

			$order->add_order_note( 'Payconiq transaction ID ' . $transaction['transactionId'] . ' is created.' );
			Wc_Gateway_Payconiq::log( 'Transaction ID( ' . $transaction['transactionId'] . ' ) is created for the order id ' . $order_id, 'info' );
		} catch ( \Exception $e ) {
			Wc_Gateway_Payconiq::log( 'Transaction is not created in Payconiq', 'error' );
			wp_die( 'Payconiq Request Failure', 'Payconiq transaction', array( 'response' => 500 ) );
		}

		/**
		 * Save Transaction ID in the order
		 */
		update_post_meta( $order_id, '_payconiq_transaction_id', $transaction['transactionId'] );

		echo '<img src="' . $transaction['qrUrl'] . '" />';

		echo '<input type="hidden" id="order_id" value="' . $order_id . '">';

		wp_enqueue_script( 'payconiq-transaction', WC_PAYCONIQ_URL . 'js/payconiq-transaction.js', array( 'jquery' ), WC_PAYCONIQ_VERSION, true );
	}

	/**
	 * Show Payconiq Transaction ID in the backend.
	 *
	 * @param $order \WC_Order
	 */
	public function show_transaction_id_in_backend( $order ) {
		echo '<p><strong>' . __( 'Payconiq Transaction ID' ) . ':</strong> <br/>' . get_post_meta( $order->get_id(), '_payconiq_transaction_id', true ) . '</p>';
	}
}