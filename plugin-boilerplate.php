<?php
/*
Plugin Name: WooCommerce - Payconiq
Plugin URI:
Description: Boilerplate plugin
Version: 1.0
Author: AppSaloon
Author URI: https://www.appsaloon.be/
License: GPLv3
*/

namespace appsaloon;

use appsaloon\lib\Container;
use appsaloon\lib\Container_Interface;

define( 'EXAMPLE_DIR', plugin_dir_path( __FILE__ ) );
define( 'EXAMPLE_URL', plugin_dir_url( __FILE__ ) );
define( 'EXAMPLE_VERSION', '1.0' );

/**
 * Register autoloader to load files/classes dynamically
 */
include_once EXAMPLE_DIR . 'lib/autoloader.php';

/**
 * Load composer/PHP-DI container
 *
 * FYI vendor files are moved from /vendor to /lib/ioc/ directory
 *
 * "php-di/php-di": "5.0"
 */
include_once EXAMPLE_DIR . 'lib/ioc/autoload.php';

class Plugin_Boilerplate {

	/**
	 * Plugin_Boilerplate constructor.
	 *
	 * @param Container_Interface $container
	 */
	public function __construct( Container_Interface $container ) {
		/**
		 * Load init config
		 */
		$container->container->get( 'init_config' );
	}
}

new Plugin_Boilerplate( Container::getInstance() );