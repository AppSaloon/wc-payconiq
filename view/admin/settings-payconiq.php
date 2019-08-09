<?php
/**
 * Settings for Payconiq Gateway.
 *
 * @package woocommerce-payconiq/Classes/Payment
 */

defined( 'ABSPATH' ) || exit;

return array(
	'enabled'               => array(
		'title'   => __( 'Enable/Disable', 'woocommerce-payconiq' ),
		'type'    => 'checkbox',
		'label'   => __( 'Enable Payconiq', 'woocommerce-payconiq' ),
		'default' => 'no',
	),
	'title'                 => array(
		'title'       => __( 'Title', 'woocommerce-payconiq' ),
		'type'        => 'text',
		'description' => __( 'This controls the title which the user sees during checkout.', 'woocommerce-payconiq' ),
		'default'     => __( 'Payconiq', 'woocommerce-payconiq' ),
		'desc_tip'    => true,
	),
	'description'           => array(
		'title'       => __( 'Description', 'woocommerce-payconiq' ),
		'type'        => 'text',
		'desc_tip'    => true,
		'description' => __( 'This controls the description which the user sees during checkout.', 'woocommerce-payconiq' ),
		'default'     => __( "Complete the order with Payconiq app.", 'woocommerce-payconiq' ),
	),
	'payment_description'           => array(
		'title'       => __( 'Payment Description', 'woocommerce-payconiq' ),
		'type'        => 'text',
		'desc_tip'    => true,
		'description' => __( 'This controls the description when the user sees the QR code.', 'woocommerce-payconiq' ),
		'default'     => __( "How to pay: Open the Payconiq app and scan the QR code. It will work for 2 minutes. After the payment, you will be automatically redirected to the order completed page.", 'woocommerce-payconiq' ),
	),
	'advanced'              => array(
		'title'       => __( 'Advanced options', 'woocommerce-payconiq' ),
		'type'        => 'title',
		'description' => '',
	),
	'testmode'              => array(
		'title'       => __( 'Payconiq sandbox', 'woocommerce-payconiq' ),
		'type'        => 'checkbox',
		'label'       => __( 'Enable Payconiq sandbox', 'woocommerce-payconiq' ),
		'default'     => 'no',
		/* translators: %s: URL */
		'description' => __( 'Payconiq sandbox can be used to test payments.', 'woocommerce-payconiq' ),
	),
	'debug'                 => array(
		'title'       => __( 'Debug log', 'woocommerce-payconiq' ),
		'type'        => 'checkbox',
		'label'       => __( 'Enable logging', 'woocommerce-payconiq' ),
		'default'     => 'no',
		/* translators: %s: URL */
		'description' => sprintf( __( 'Log Payconiq events, inside %s Note: this may log personal information. We recommend using this for debugging purposes only and deleting the logs when finished.', 'woocommerce-payconiq' ), '<code>' . WC_Log_Handler_File::get_log_file_path( 'payconiq' ) . '</code>' ),
	),
	'image_url'             => array(
		'title'       => __( 'Image url', 'woocommerce-payconiq' ),
		'type'        => 'text',
		'description' => __( 'Optionally enter the URL to a 150x50px image displayed as your logo in the upper left corner of the Payconiq checkout pages.', 'woocommerce-payconiq' ),
		'default'     => '',
		'desc_tip'    => true,
		'placeholder' => __( 'Optional', 'woocommerce-payconiq' ),
	),
	'api_details'           => array(
		'title'       => __( 'API credentials', 'woocommerce-payconiq' ),
		'type'        => 'title',
		/* translators: %s: URL */
		'description' => __( 'Enter your Payconiq API credentials to process refunds via Payconiq.', 'woocommerce-payconiq' ),
	),
	'api_merchant_id'          => array(
		'title'       => __( 'Merchant API ID', 'woocommerce-payconiq' ),
		'type'        => 'text',
		'description' => __( 'Get your API credentials from Payconiq.', 'woocommerce-payconiq' ),
		'default'     => '',
		'desc_tip'    => true,
		'placeholder' => __( 'Optional', 'woocommerce-payconiq' ),
	),
	'api_key'          => array(
		'title'       => __( 'Live API key', 'woocommerce-payconiq' ),
		'type'        => 'text',
		'description' => __( 'Get your API credentials from Payconiq.', 'woocommerce-payconiq' ),
		'default'     => '',
		'desc_tip'    => true,
		'placeholder' => __( 'Optional', 'woocommerce-payconiq' ),
	),
	'sandbox_api_merchant_id'          => array(
		'title'       => __( 'Sandbox Merchant API ID', 'woocommerce-payconiq' ),
		'type'        => 'text',
		'description' => __( 'Get your API credentials from Payconiq.', 'woocommerce-payconiq' ),
		'default'     => '',
		'desc_tip'    => true,
		'placeholder' => __( 'Optional', 'woocommerce-payconiq' ),
	),
	'sandbox_api_key'  => array(
		'title'       => __( 'Sandbox API key', 'woocommerce-payconiq' ),
		'type'        => 'text',
		'description' => __( 'Get your API credentials from Payconiq.', 'woocommerce-payconiq' ),
		'default'     => '',
		'desc_tip'    => true,
		'placeholder' => __( 'Optional', 'woocommerce-payconiq' ),
	),
);