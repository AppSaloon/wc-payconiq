<?php

namespace appsaloon\config;

use appsaloon\config\helper\Genre_Taxonomy;
use appsaloon\config\metabox\Information;
use appsaloon\controller\Example_Controller;
use appsaloon\lib\Container;

class Example_Config extends Base_Config {

	// information metabox toevoegen (trait)
	// genre taxonomy toevoegen (trait)
	use Information, Genre_Taxonomy;

	CONST POST_TYPE = 'example';

	CONST SINGULAR_NAME = 'example';

	CONST PLURAL_NAME = 'examples';

	CONST DESCRIPTION = 'Example.';

	/**
	 * @var Example_Controller
	 */
	protected $controller;

	/**
	 * Settings container
	 *
	 * @var \DI\Container
	 */
	protected $settings;

	/**
	 * Example_Config constructor.
	 *
	 * @throws \DI\DependencyException
	 * @throws \DI\NotFoundException
	 */
	public function __construct() {
		$this->post_type     = static::POST_TYPE;
		$this->singular_name = static::SINGULAR_NAME;
		$this->plural_name   = static::PLURAL_NAME;
		$this->description   = static::DESCRIPTION;

		// registreren van custom post type
		parent::__construct();

		// custom post type model - updaten van meta velden
		$this->model = Container::getInstance()->container->get('example_model');

		// controller
		$this->controller = Container::getInstance()->container->get( 'example_controller' );

		// hier kan je nog metaboxen toevoegen of extra wordpress opties
		$this->add_information_meta_box();

		// test taxonomy toevoegen
		$this->add_genre_taxonomy();
	}

	/**
	 * Opslaan van custom meta_velden
	 *
	 * @param $post_id
	 * @param $post
	 * @param $update
	 */
	public function save_meta( $post_id, $post, $update ) {
		if ( $update ) {
			$this->controller->save( $this->model, $post_id, true );
		}
	}
}