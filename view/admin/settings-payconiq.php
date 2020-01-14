<?php
/**
 * Settings for Payconiq Gateway.
 *
 * @package wc-payconiq/Classes/Payment
 */

defined( 'ABSPATH' ) || exit;

return array(
	'enabled'                 => array(
		'title'   => esc_html__( 'Enable/Disable', 'wc-payconiq' ),
		'type'    => 'checkbox',
		'label'   => esc_html__( 'Enable Payconiq', 'wc-payconiq' ),
		'default' => 'no',
	),
	'title'                   => array(
		'title'       => esc_html__( 'Title', 'wc-payconiq' ),
		'type'        => 'text',
		'description' => esc_html__( 'This controls the title which the user sees during checkout.', 'wc-payconiq' ),
		'default'     => esc_html__( 'Payconiq', 'wc-payconiq' ),
		'desc_tip'    => true,
	),
	'description'             => array(
		'title'       => esc_html__( 'Description', 'wc-payconiq' ),
		'type'        => 'text',
		'desc_tip'    => true,
		'description' => esc_html__( 'This controls the description which the user sees during checkout.',
			'wc-payconiq' ),
		'default'     => esc_html__( "Complete the order with Payconiq app.", 'wc-payconiq' ),
	),
	'payment_description'     => array(
		'title'       => esc_html__( 'Payment Description', 'wc-payconiq' ),
		'type'        => 'text',
		'desc_tip'    => true,
		'description' => esc_html__( 'This controls the description when the user sees the QR code.', 'wc-payconiq' ),
		'default'     => esc_html__( "How to pay: Open the Payconiq app and scan the QR code. It will work for 2 minutes. After the payment, you will be automatically redirected to the order completed page.",
			'wc-payconiq' ),
	),
	'advanced'                => array(
		'title'       => esc_html__( 'Advanced options', 'wc-payconiq' ),
		'type'        => 'title',
		'description' => '',
	),
	'testmode'                => array(
		'title'       => esc_html__( 'Payconiq sandbox', 'wc-payconiq' ),
		'type'        => 'checkbox',
		'label'       => esc_html__( 'Enable Payconiq sandbox', 'wc-payconiq' ),
		'default'     => 'no',
		/* translators: %s: URL */
		'description' => esc_html__( 'Payconiq sandbox can be used to test payments.', 'wc-payconiq' ),
	),
	'debug'                   => array(
		'title'       => esc_html__( 'Debug log', 'wc-payconiq' ),
		'type'        => 'checkbox',
		'label'       => esc_html__( 'Enable logging', 'wc-payconiq' ),
		'default'     => 'no',
		/* translators: %s: URL */
		'description' => sprintf( esc_html__( 'Log Payconiq events, inside %s Note: this may log personal information. We recommend using this for debugging purposes only and deleting the logs when finished.',
			'wc-payconiq' ), '<code>' . WC_Log_Handler_File::get_log_file_path( 'payconiq' ) . '</code>' ),
	),
	'image_url'               => array(
		'title'       => esc_html__( 'Image url', 'wc-payconiq' ),
		'type'        => 'text',
		'description' => esc_html__( 'Optionally enter the URL to a 150x50px image displayed as your logo in the upper left corner of the Payconiq checkout pages.',
			'wc-payconiq' ),
		'default'     => '',
		'desc_tip'    => true,
		'placeholder' => esc_html__( 'Optional', 'wc-payconiq' ),
	),
	'api_details'             => array(
		'title'       => esc_html__( 'API credentials', 'wc-payconiq' ),
		'type'        => 'title',
		/* translators: %s: URL */
		'description' => esc_html__( 'Enter your Payconiq API credentials to process refunds via Payconiq.',
			'wc-payconiq' ),
	),
	'api_merchant_id'         => array(
		'title'       => esc_html__( 'Merchant API ID', 'wc-payconiq' ),
		'type'        => 'text',
		'description' => esc_html__( 'Get your API credentials from Payconiq.', 'wc-payconiq' ),
		'default'     => '',
		'desc_tip'    => true,
		'placeholder' => esc_html__( 'Optional', 'wc-payconiq' ),
	),
	'api_key'                 => array(
		'title'       => esc_html__( 'Live API key', 'wc-payconiq' ),
		'type'        => 'text',
		'description' => esc_html__( 'Get your API credentials from Payconiq.', 'wc-payconiq' ),
		'default'     => '',
		'desc_tip'    => true,
		'placeholder' => esc_html__( 'Optional', 'wc-payconiq' ),
	),
	'sandbox_api_merchant_id' => array(
		'title'       => esc_html__( 'Sandbox Merchant API ID', 'wc-payconiq' ),
		'type'        => 'text',
		'description' => esc_html__( 'Get your API credentials from Payconiq.', 'wc-payconiq' ),
		'default'     => '',
		'desc_tip'    => true,
		'placeholder' => esc_html__( 'Optional', 'wc-payconiq' ),
	),
	'sandbox_api_key'         => array(
		'title'       => esc_html__( 'Sandbox API key', 'wc-payconiq' ),
		'type'        => 'text',
		'description' => esc_html__( 'Get your API credentials from Payconiq.', 'wc-payconiq' ),
		'default'     => '',
		'desc_tip'    => true,
		'placeholder' => esc_html__( 'Optional', 'wc-payconiq' ),
	),
);