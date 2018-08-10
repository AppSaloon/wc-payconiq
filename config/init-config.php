<?php

namespace wc_payconiq\config;

use wc_payconiq\lib\Container;

class Init_Config {

	/**
	 * @var \DI\container
	 */
	private $container;

	/**
	 * Init_Config constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->container = Container::getInstance();


	}
}