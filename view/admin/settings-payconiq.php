<?php
/**
 * Settings for Payconiq Gateway.
 *
 * @package wc-payconiq/Classes/Payment
 */

defined( 'ABSPATH' ) || exit;

return array(
	'enabled'               => array(
		'title'   => __( 'Enable/Disable', 'wc-payconiq' ),
		'type'    => 'checkbox',
		'label'   => __( 'Enable Payconiq', 'wc-payconiq' ),
		'default' => 'no',
	),
	'title'                 => array(
		'title'       => __( 'Title', 'wc-payconiq' ),
		'type'        => 'text',
		'description' => __( 'This controls the title which the user sees during checkout.', 'wc-payconiq' ),
		'default'     => __( 'Payconiq', 'wc-payconiq' ),
		'desc_tip'    => true,
	),
	'description'           => array(
		'title'       => __( 'Description', 'wc-payconiq' ),
		'type'        => 'text',
		'desc_tip'    => true,
		'description' => __( 'This controls the description which the user sees during checkout.', 'wc-payconiq' ),
		'default'     => __( "Complete the order with Payconiq app.", 'wc-payconiq' ),
	),
	'payment_description'           => array(
		'title'       => __( 'Payment Description', 'wc-payconiq' ),
		'type'        => 'text',
		'desc_tip'    => true,
		'description' => __( 'This controls the description when the user sees the QR code.', 'wc-payconiq' ),
		'default'     => __( "How to pay: Open the Payconiq app and scan the QR code. It will work for 2 minutes. After the payment, you will be automatically redirected to the order completed page.", 'wc-payconiq' ),
	),
	'advanced'              => array(
		'title'       => __( 'Advanced options', 'wc-payconiq' ),
		'type'        => 'title',
		'description' => '',
	),
	'testmode'              => array(
		'title'       => __( 'Payconiq sandbox', 'wc-payconiq' ),
		'type'        => 'checkbox',
		'label'       => __( 'Enable Payconiq sandbox', 'wc-payconiq' ),
		'default'     => 'no',
		/* translators: %s: URL */
		'description' => __( 'Payconiq sandbox can be used to test payments.', 'wc-payconiq' ),
	),
	'debug'                 => array(
		'title'       => __( 'Debug log', 'wc-payconiq' ),
		'type'        => 'checkbox',
		'label'       => __( 'Enable logging', 'wc-payconiq' ),
		'default'     => 'no',
		/* translators: %s: URL */
		'description' => sprintf( __( 'Log Payconiq events, inside %s Note: this may log personal information. We recommend using this for debugging purposes only and deleting the logs when finished.', 'wc-payconiq' ), '<code>' . WC_Log_Handler_File::get_log_file_path( 'payconiq' ) . '</code>' ),
	),
	'image_url'             => array(
		'title'       => __( 'Image url', 'wc-payconiq' ),
		'type'        => 'text',
		'description' => __( 'Optionally enter the URL to a 150x50px image displayed as your logo in the upper left corner of the Payconiq checkout pages.', 'wc-payconiq' ),
		'default'     => '',
		'desc_tip'    => true,
		'placeholder' => __( 'Optional', 'wc-payconiq' ),
	),
	'api_details'           => array(
		'title'       => __( 'API credentials', 'wc-payconiq' ),
		'type'        => 'title',
		/* translators: %s: URL */
		'description' => __( 'Enter your Payconiq API credentials to process refunds via Payconiq.', 'wc-payconiq' ),
	),
	'api_merchant_id'          => array(
		'title'       => __( 'Merchant API ID', 'wc-payconiq' ),
		'type'        => 'text',
		'description' => __( 'Get your API credentials from Payconiq.', 'wc-payconiq' ),
		'default'     => '',
		'desc_tip'    => true,
		'placeholder' => __( 'Optional', 'wc-payconiq' ),
	),
	'api_key'          => array(
		'title'       => __( 'Live API key', 'wc-payconiq' ),
		'type'        => 'text',
		'description' => __( 'Get your API credentials from Payconiq.', 'wc-payconiq' ),
		'default'     => '',
		'desc_tip'    => true,
		'placeholder' => __( 'Optional', 'wc-payconiq' ),
	),
	'sandbox_api_merchant_id'          => array(
		'title'       => __( 'Sandbox Merchant API ID', 'wc-payconiq' ),
		'type'        => 'text',
		'description' => __( 'Get your API credentials from Payconiq.', 'wc-payconiq' ),
		'default'     => '',
		'desc_tip'    => true,
		'placeholder' => __( 'Optional', 'wc-payconiq' ),
	),
	'sandbox_api_key'  => array(
		'title'       => __( 'Sandbox API key', 'wc-payconiq' ),
		'type'        => 'text',
		'description' => __( 'Get your API credentials from Payconiq.', 'wc-payconiq' ),
		'default'     => '',
		'desc_tip'    => true,
		'placeholder' => __( 'Optional', 'wc-payconiq' ),
	),
);