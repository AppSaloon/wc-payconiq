<?php
/**
 * PHPUnit bootstrap file
 *
 * @package Klasse_Plugin_Boilerplate
 */

$_tests_dir = getenv( 'WP_TESTS_DIR' );
if ( ! $_tests_dir ) {
	$_tests_dir = '/tmp/wordpress-tests-lib';
}

// Give access to tests_add_filter() function.
require_once $_tests_dir . '/includes/functions.php';

// phpunit heeft geen omgeving - hiermee zetten we de omgeving op local
define( 'PHPUNIT_TESTSUITE', true );

/**
 * Manually load the plugin being tested.
 */
function _manually_load_plugin() {
	// plugin
	require dirname( dirname( __FILE__ ) ) . '/woocommerce-payconiq.php';
}
tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );

// Start up the WP testing environment.
require $_tests_dir . '/includes/bootstrap.php';
