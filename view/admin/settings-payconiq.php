<?php
/**
 * Settings for Payconiq Gateway.
 *
 * @package WooCommerce/Classes/Payment
 */

defined( 'ABSPATH' ) || exit;

return array(
	'enabled'               => array(
		'title'   => __( 'Enable/Disable', 'woocommerce' ),
		'type'    => 'checkbox',
		'label'   => __( 'Enable Payconiq', 'woocommerce' ),
		'default' => 'no',
	),
	'title'                 => array(
		'title'       => __( 'Title', 'woocommerce' ),
		'type'        => 'text',
		'description' => __( 'This controls the title which the user sees during checkout.', 'woocommerce' ),
		'default'     => __( 'Payconiq', 'woocommerce' ),
		'desc_tip'    => true,
	),
	'description'           => array(
		'title'       => __( 'Description', 'woocommerce' ),
		'type'        => 'text',
		'desc_tip'    => true,
		'description' => __( 'This controls the description which the user sees during checkout.', 'woocommerce' ),
		'default'     => __( "Pay via Payconiq; you can pay with your Payconiq app.", 'woocommerce' ),
	),
	'advanced'              => array(
		'title'       => __( 'Advanced options', 'woocommerce' ),
		'type'        => 'title',
		'description' => '',
	),
	'testmode'              => array(
		'title'       => __( 'Payconiq sandbox', 'woocommerce' ),
		'type'        => 'checkbox',
		'label'       => __( 'Enable Payconiq sandbox', 'woocommerce' ),
		'default'     => 'no',
		/* translators: %s: URL */
		'description' => __( 'Payconiq sandbox can be used to test payments.', 'woocommerce' ),
	),
	'debug'                 => array(
		'title'       => __( 'Debug log', 'woocommerce' ),
		'type'        => 'checkbox',
		'label'       => __( 'Enable logging', 'woocommerce' ),
		'default'     => 'no',
		/* translators: %s: URL */
		'description' => sprintf( __( 'Log Payconiq events, inside %s Note: this may log personal information. We recommend using this for debugging purposes only and deleting the logs when finished.', 'woocommerce' ), '<code>' . WC_Log_Handler_File::get_log_file_path( 'payconiq' ) . '</code>' ),
	),
	'image_url'             => array(
		'title'       => __( 'Image url', 'woocommerce' ),
		'type'        => 'text',
		'description' => __( 'Optionally enter the URL to a 150x50px image displayed as your logo in the upper left corner of the Payconiq checkout pages.', 'woocommerce' ),
		'default'     => '',
		'desc_tip'    => true,
		'placeholder' => __( 'Optional', 'woocommerce' ),
	),
	'api_details'           => array(
		'title'       => __( 'API credentials', 'woocommerce' ),
		'type'        => 'title',
		/* translators: %s: URL */
		'description' => __( 'Enter your Payconiq API credentials to process refunds via Payconiq.', 'woocommerce' ),
	),
	'api_key'          => array(
		'title'       => __( 'Live API key', 'woocommerce' ),
		'type'        => 'text',
		'description' => __( 'Get your API credentials from Payconiq.', 'woocommerce' ),
		'default'     => '',
		'desc_tip'    => true,
		'placeholder' => __( 'Optional', 'woocommerce' ),
	),
	'sandbox_api_key'  => array(
		'title'       => __( 'Sandbox API key', 'woocommerce' ),
		'type'        => 'text',
		'description' => __( 'Get your API credentials from Payconiq.', 'woocommerce' ),
		'default'     => '',
		'desc_tip'    => true,
		'placeholder' => __( 'Optional', 'woocommerce' ),
	),
);