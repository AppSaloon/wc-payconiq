<?php

/**
 * Class Wc_Payconic_Autoloader
 *
 * @since 1.0.0
 */
class Wc_Payconiq_Autoloader {

	/**
	 * plugin root namespace
	 *
	 * @sice 1.0.0
	 */
	const ROOT_NAMESPACE = 'wc_payconiq\\';

	/**
	 * Register autoload method
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		spl_autoload_register( array( $this, 'wc_payconiq_autoloader_callback' ) );
	}

	/**
	 * Includes file from the correct namespace
	 * else it will do nothing
	 *
	 * @param $class
	 *
	 * @since 1.0.0
	 */
	public function wc_payconiq_autoloader_callback($class) {
		if ( strpos( $class, self::ROOT_NAMESPACE ) === 0 ) {
			$path = substr( $class, strlen( self::ROOT_NAMESPACE ) );
			$path = strtolower( $path );
			$path = str_replace( '_', '-', $path );
			$path = str_replace( '\\', DIRECTORY_SEPARATOR, $path ) . '.php';
			$path = WC_PAYCONIQ_DIR . DIRECTORY_SEPARATOR . $path;

			if ( file_exists( $path ) ) {
				include $path;
			}
		}
	}
}

/**
 * Start autoloader
 *
 * @since 1.0.0
 */
new Wc_Payconiq_Autoloader();