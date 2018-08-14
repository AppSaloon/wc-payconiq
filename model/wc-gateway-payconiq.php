<?php

namespace wc_payconiq\model;

use wc_payconiq\lib\Payconiq_Client;

class Wc_Gateway_Payconiq extends \WC_Payment_Gateway {

	CONST ID = 'Wc_Gateway_Payconiq';

	/**
	 * Whether or not logging is enabled
	 *
	 * @var bool
	 *
	 * @since 1.0.0
	 */
	public static $log_enabled = false;

	/**
	 * Logger instance
	 *
	 * @var \WC_Logger
	 *
	 * @since 1.0.0
	 */
	public static $log = false;

	/**
	 * Is it Sandbox (false) or Production (true)
	 * @var bool
	 *
	 * @since 1.0.0
	 */
	protected $testmode;

	/**
	 * Constructor for the gateway.
	 *
	 * @since 1.0.0
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
		add_action( 'woocommerce_api_wc_gateway_payconiq', array( $this, 'check_response' ) );

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
	 *
	 * @since 1.0.0
	 */
	public function init_form_fields() {
		$this->form_fields = include WC_PAYCONIQ_DIR . 'view/admin/settings-payconiq.php';
	}

	/**
	 * Processes and saves options.
	 * If there is an error thrown, will continue to save and validate fields, but will leave the erroring field out.
	 *
	 * @return bool was anything saved?
	 *
	 * @since 1.0.0
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
	 *
	 * @since 1.0.0
	 */
	public static function log( $message, $level = 'info' ) {
		if ( self::$log_enabled ) {
			if ( empty( self::$log ) ) {
				self::$log = wc_get_logger();
			}
			self::$log->log( $level, $message, array( 'source' => 'payconiq' ) );
		}
	}

	/**
	 * Redirect from checkout page to payment page
	 *
	 * @param int $order_id
	 *
	 * @return array
	 *
	 * @since 1.0.0
	 */
	public function process_payment( $order_id ) {
		$order = wc_get_order( $order_id );

		return array(
			'result'   => 'success',
			'redirect' => $order->get_checkout_payment_url( true ),
		);
	}

	/**
	 * * Renders the receipt page.
	 *
	 * @param int $order_id WooCommerce Order ID.
	 *
	 * @throws \WC_Data_Exception
	 *
	 * @since 1.0.0
	 */
	public function render_receipt_page( $order_id ) {
		$order = wc_get_order( $order_id );

		/**
		 * If currency is not EUR
		 */
		if ( $order->get_currency() != 'EUR' ) {
			wp_die( 'The payconiq works only with EUR ', array(
				'response',
				403
			) );
			exit;
		}

		/**
		 * Create callback url
		 */
		$callbackUrl = str_replace( 'http://', 'https://', add_query_arg( 'wc-api', 'wc_gateway_payconiq', home_url( '/' ) ) );

		/**
		 * Add order ID to callback url
		 */
		$callbackUrl = add_query_arg( 'webhookId', $order_id, $callbackUrl );

		$payconiq = $this->get_payconiq_client();

		if ( $payconiq == false ) {
			$this->log( 'Payconiq credentials are not filled in', 'error' );
			wp_die( 'Payconiq credentials are not filled in!' );
		}

		/**
		 * Create Transaction ID
		 */
		try {
			$transaction = $payconiq->createTransaction( $order->get_total() * 100, $order->get_currency(), $callbackUrl, true );

			$order->add_order_note( 'Payconiq transaction ID ' . $transaction['transactionId'] . ' is created.' );
			$this->log( 'Transaction ID( ' . $transaction['transactionId'] . ' ) is created for the order id ' . $order_id, 'info' );
		} catch ( \Exception $e ) {
			$this->log( 'Transaction is not created in Payconiq', 'error' );
			wp_die( 'Payconiq Request Failure', 'Payconiq transaction', array( 'response' => 500 ) );
		}

		/**
		 * Save Transaction ID in the order
		 */
		$order->set_transaction_id( $transaction['transactionId'] );
		$order->save();

		update_post_meta( $order_id, '_payconiq_transaction_id', $transaction['transactionId'] );

		/**
		 * Display QR code
		 */
		echo '<img src="' . $transaction['qrUrl'] . '" />';

		/**
		 * Save Order ID for javascript
		 */
		echo '<input type="hidden" id="order_id" value="' . $order_id . '">';

		/**
		 * Show more information about the Payconiq App.
		 */
		echo '<p id="payconiq_message">' . $this->get_option('payment_description' ) . '</p>';

		wp_enqueue_script( 'payconiq-transaction', WC_PAYCONIQ_URL . 'js/payconiq-transaction.js', array( 'jquery' ), WC_PAYCONIQ_VERSION, true );
	}

	/**
	 * Show Payconiq Transaction ID in the backend.
	 *
	 * @param $order \WC_Order
	 *
	 * @since 1.0.0
	 */
	public function show_transaction_id_in_backend( $order ) {
		echo '<p><strong>' . __( 'Payconiq Transaction ID' ) . ':</strong> <br/>' . get_post_meta( $order->get_id(), '_payconiq_transaction_id', true ) . '</p>';
	}

	/**
	 * Process Payconiq callback Response.
	 *
	 * @since 1.0.0
	 */
	public function check_response() {
		$order_id = ( isset( $_GET['webhookId'] ) ) ? $_GET['webhookId'] : false;

		if ( $order_id == false ) {
			error_log( 'The order ID is not provided: ' . $order_id );
			$this->log( 'The order ID is not provided: ' . $order_id, 'error' );

			return;
		}

		$order = wc_get_order( $order_id );

		if ( $order == false ) {
			error_log( 'The order ID is not valid: ' . $order_id );
			$this->log( 'The order ID is not valid: ' . $order_id, 'error' );

			return;
		}

		$payconiq = $this->get_payconiq_client();

		if ( $payconiq == false ) {
			$this->log( 'Payconiq credentials are not filled in', 'error' );
			wp_die( 'Payconiq credentials are not filled in!' );
		}

		$transaction_id = $order->get_transaction_id();

		if ( empty( $transaction_id ) ) {
			error_log( 'Transaction ID is not found.' );
			$this->log( 'Transaction ID is not found.', 'error' );

			return;
		}

		try {
			$response = $payconiq->retrieveTransaction( $transaction_id, true );
		} catch ( \Exception $e ) {
			$this->log( 'Something went wrong with retrieving the transaction.', 'error' );
			error_log( 'Something went wrong with retrieving the transaction.' );

			return;
		}

		switch ( $response['status'] ) {
			case 'SUCCEEDED':
				$order->payment_complete();
				$order->update_status( 'completed' );
				$order->add_order_note( 'The order is completed in Payconiq.' );
				$this->log( 'The order is completed in Payconiq' );
				break;
			case 'CREATION':
			case 'PENDING':
			case 'CONFIRMED':
				$order->update_status( 'pending' );
				$order->add_order_note( 'Order is pending due to payconiq order status: ' . $response['status'] );
				$this->log( 'Order is pending due to payconiq order status: ' . $response['status'] );
				break;
			case 'CANCELED':
			case 'CANCELED_BY_MERCHANT':
			case 'FAILED':
			case 'TIMEDOUT':
			case 'BLOCKED':
				$order->update_status( 'cancelled' );
				$order->add_order_note( 'Order is cancelled due to payconiq order status: ' . $response['status'] );
				$this->log( 'Order is pending due to payconiq order status: ' . $response['status'], 'error' );
				break;
		}

		/**
		 * Save order changes
		 */
		$order->save();
	}

	/**
	 * Can the order be refunded via Payconiq?
	 *
	 * @param  \WC_Order $order Order object.
	 *
	 * @return bool
	 *
	 * @since 1.0.0
	 */
	public function can_refund_order( $order ) {
		$has_api_creds = false;

		if ( $this->testmode ) {
			$has_api_creds = $this->get_option( 'sandbox_api_merchant_id' ) && $this->get_option( 'sandbox_api_key' );
		} else {
			$has_api_creds = $this->get_option( 'api_merchant_id' ) && $this->get_option( 'api_key' );
		}

		return $order && $order->get_transaction_id() && $has_api_creds;
	}

	/**
	 * Process a refund if supported.
	 *
	 * @param  int $order_id Order ID.
	 * @param  float $amount Refund amount.
	 * @param  string $reason Refund reason.
	 *
	 * @return bool|\WP_Error
	 *
	 * @since 1.0.0
	 */
	public function process_refund( $order_id, $amount = null, $reason = '' ) {
		$order = wc_get_order( $order_id );

		/**
		 * If currency is not EUR
		 */
		if ( $order->get_currency() != 'EUR' ) {
			$this->log( __( 'payconiq works only with EUR', 'wc-payconiq' ), 'error' );

			return new \WP_Error( 'error', __( 'payconiq works only with EUR', 'wc-payconiq' ) );
		}

		if ( ! $this->can_refund_order( $order ) ) {
			$this->log( __( 'Refund failed due to invalid credentials.', 'wc-payconiq' ), 'error' );

			return new \WP_Error( 'error', __( 'Refund failed due to invalid credentials.', 'wc-payconiq' ) );
		}

		$transaction_id = $order->get_transaction_id();

		if ( ! $transaction_id ) {
			$this->log( __( 'Transaction ID is not found.', 'wc-payconiq' ), 'error' );

			return new \WP_Error( 'error', __( 'Transaction ID is not found.', 'wc-payconiq' ) );
		}

		$payconiq = $this->get_payconiq_client();

		if ( $payconiq == false ) {
			$this->log( __( 'Payconiq credentials are not filled in', 'wc-payconiq' ), 'error' );

			return new \WP_Error( 'error', __( 'Payconiq credentials are not filled in', 'wc-payconiq' ) );
		}


		try {
			$result = $payconiq->createRefund( $transaction_id, $amount * 100, $order->get_currency(), 'SDD', $reason );

			$this->log( 'Refund Result: ' . wc_print_r( $result, true ) );

			$order->add_order_note(
			/* translators: 1: Refund amount, 2: Refund ID */
				sprintf( __( 'Refunded %1$s - Refund ID: %2$s', 'woocommerce' ), $result['amount'], $result['_id'] )
			);

			return true;
		} catch ( \Exception $e ) {
			$this->log( __( 'Refund Failed: ', 'wc-payconiq' ) . $e->getMessage(), 'error' );

			return new \WP_Error( 'error', __( 'Refund Failed: ', 'wc-payconiq' ) . $e->getMessage() );
		}
	}

	/**
	 * Returns Payconiq client - which connects to Payconiq API
	 *
	 * @return bool|Payconiq_Client
	 *
	 * @since 1.0.0
	 */
	protected function get_payconiq_client() {
		$payconiq = false;

		if ( $this->testmode ) {
			if ( $this->get_option( 'sandbox_api_merchant_id' ) && $this->get_option( 'sandbox_api_key' ) ) {
				$payconiq = new Payconiq_Client( $this->get_option( 'sandbox_api_merchant_id' ), $this->get_option( 'sandbox_api_key' ), true );
			}
		} else {
			if ( $this->get_option( 'api_merchant_id' ) && $this->get_option( 'api_key' ) ) {
				$payconiq = new Payconiq_Client( $this->get_option( 'api_merchant_id' ), $this->get_option( 'api_key' ), false );
			}
		}

		return $payconiq;
	}
}