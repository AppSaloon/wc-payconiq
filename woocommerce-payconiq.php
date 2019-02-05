<?php
/*
Plugin Name: Payconiq for WooCommerce
Plugin URI:
Description: Accept payments by scanning a QR-code through the Payconiq app. Makes it even more easy for your customers to order!
Version: 1.0.1
Author: AppSaloon
Author URI: https://www.appsaloon.be/
License: GPLv3
*/

namespace wc_payconiq;

use wc_payconiq\lib\Container;
use wc_payconiq\lib\Container_Interface;

define( 'WC_PAYCONIQ_DIR', plugin_dir_path( __FILE__ ) );
define( 'WC_PAYCONIQ_URL', plugin_dir_url( __FILE__ ) );
define( 'WC_PAYCONIQ_FILE', __FILE__ );
define( 'WC_PAYCONIQ_VERSION', '1.0.1' );

/**
 * Register autoloader to load files/classes dynamically
 */
require_once WC_PAYCONIQ_DIR . 'lib/autoloader.php';

/**
 * Load composer/PHP-DI container
 *
 * FYI vendor files are moved from /vendor to /lib/ioc/ directory
 *
 * "php-di/php-di": "5.0"
 *
 * @version 1.0.0
 * @since 1.0.0
 */
require_once WC_PAYCONIQ_DIR . 'lib/ioc/autoload.php';

class Woocommerce_Payconiq {

	/**
	 * Plugin_Boilerplate constructor.
	 *
	 * @param Container_Interface $container
	 *
	 * @version 1.0.0
	 * @since 1.0.0
	 */
	public function __construct( Container_Interface $container ) {
		/**
		 * Load init config
		 *
		 * @version 1.0.0
		 * @since 1.0.0
		 */
		$container->container->get( 'init_config' );
	}
}

/**
 * Start the plugin
 *
 * @version 1.0.0
 * @since 1.0.0
 */
new Woocommerce_Payconiq( Container::getInstance() );
