<?php

namespace wc_payconiq\config;

use DI\DependencyException;
use DI\NotFoundException;
use wc_payconiq\lib\Container;
use wc_payconiq\model\Wc_Gateway_Payconiq;

class Init_Config {

	/**
	 * @var \DI\container
	 */
	private $container;

	/**
	 * Init_Config constructor.
	 * @throws \DI\DependencyException
	 * @throws \DI\NotFoundException
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->container = Container::getInstance();

		$this->container->container->get( 'payconiq_config' );
	}
}