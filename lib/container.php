<?php

namespace appsaloon\lib;

use \DI;
use DI\ContainerBuilder;

Final class Container implements Container_Interface {

	/**
	 * @var \DI\ContainerBuilder
	 */
	protected $builder;

	/**
	 * @var \DI\Container
	 */
	public $container;

	/**
	 * @var Container
	 */
	protected static $instance;

	/**
	 * Build Container.
	 */
	public function __construct() {
		$this->builder = new ContainerBuilder();

		$this->build_container();

		$this->set_classes();
	}

	/**
	 * Instance of this class
	 *
	 * @return Container
	 */
	public static function getInstance()
	{
		if( null == static::$instance ){
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Build Container
	 */
	public function build_container() {
		$this->builder->addDefinitions( [
			'plugin_activate'   => DI\object( 'appsaloon\config\Plugin_Activate' ),
			'plugin_deactivate' => DI\object( 'appsaloon\config\Plugin_Deactivate' ),
			'log'               => DI\object( 'appsaloon\lib\Log' )
		] );

		$this->container = $this->builder->build();

		/**
		 * Set init config with container as parameter
		 */
		$this->container->set( 'init_config', DI\object( 'appsaloon\config\Example_Config' ) );
	}

	/**
	 * Set classes that needs to be used
	 */
	public function set_classes() {
		$this->container->set( 'example_controller', \DI\object( 'appsaloon\controller\Example_Controller' ) );
		$this->container->set( 'base_controller', \DI\object( 'appsaloon\controller\Base_Controller' ) );
		$this->container->set( 'example_model', \DI\object( 'appsaloon\model\Example_Model' ) );
	}
}