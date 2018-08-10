<?php
namespace appsaloon\config;

use appsaloon\lib\Container;

abstract class Base_Config {
	
	/**
	 * @var object class naam van het model
	 */
	protected $post_type;

	/**
	 * @var string enkel en meervoudige naam van het model
	 */
	protected $singular_name;
	protected $plural_name;

	/**
	 * @var string  omschrijven van het model
	 */
	protected $description;

	/**
	 * @var string  menu slug
	 */
	protected $menu_slug = 'example';

	/**
	 * @var \appsaloon\model\Base_Model
	 */
	public $model;

	public function __construct() {
		add_action( 'init', array( $this, 'set_custom_post_type' ) );

		add_action( 'save_post_' . $this->post_type, array( $this, 'save_meta' ), 10, 3 );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_script' ) );
	}

	public function set_custom_post_type() {
		$labels = array(
			'name'               => $this->plural_name,
			'singular_name'      => $this->singular_name,
			'menu_name'          => $this->plural_name,
			'name_admin_bar'     => $this->singular_name,
			'add_new'            => 'Add New',
			'add_new_item'       => 'Add New ' . $this->singular_name,
			'new_item'           => 'New ' . $this->singular_name,
			'edit_item'          => 'Edit ' . $this->singular_name,
			'view_item'          => 'View ' . $this->singular_name,
			'all_items'          => $this->plural_name,
			'search_items'       => 'Search ' . $this->plural_name,
			'parent_item_colon'  => 'Parent ' . $this->plural_name . ':',
			'not_found'          => 'No ' . $this->plural_name . ' found.',
			'not_found_in_trash' => 'No ' . $this->plural_name . ' found in Trash.'
		);

		$args = array(
			'labels'              => $labels,
			'description'         => __( $this->description, 'klasse' ),
			'public'              => true,
			'publicly_queryable'  => false,
			'exclude_from_search' => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'query_var'           => true,
			'capability_type'     => 'post',
			'has_archive'         => false,
			'hierarchical'        => false,
			'menu_position'       => null,
			'supports'            => array( 'title' ),
			'show_in_rest'        => true
		);

		register_post_type( $this->post_type, $args );
	}

	/**
	 * Opslaan van meta_velden
	 *
	 * @param $post_id
	 * @param $post
	 * @param $update
	 *
	 * @throws \DI\DependencyException
	 * @throws \DI\NotFoundException
	 */
	public function save_meta( $post_id, $post, $update ) {
		$controller = Container::getInstance()->container->get('base_controller');
		$controller->save( $this->model, $post_id );
	}

	/**
	 * Javascript en css laden via custom post type naam of base.
	 *
	 * Is dit wel nodig voor klasse?
	 *
	 * Bijv: example.js of example.css
	 */
	public function admin_script() {
		global $post;

		if ( isset( $post->post_type ) && $post->post_type == $this->post_type ) {
			/**
			 * CUSTOM POST TYPE CSS & JS
			 */
			$js_dir = EXAMPLE_DIR . 'js/' . $this->post_type . '.js';
			$js_url = EXAMPLE_URL . 'js/' . $this->post_type . '.js';

			$css_dir = EXAMPLE_DIR . 'style/css/' . $this->post_type . '.css';
			$css_url = EXAMPLE_URL . 'style/css/' . $this->post_type . '.css';


			if ( is_file( $css_dir ) ) {
				wp_enqueue_style( $this->post_type . '-style', $css_url, array(), EXAMPLE_VERSION );
			}

			/**
			 * Base CSS & JS
			 */
			$base_js_dir = EXAMPLE_DIR . 'js/base.js';
			$base_js_url = EXAMPLE_URL . 'js/base.js';

			$base_css_dir = EXAMPLE_DIR . 'style/css/base.css';
			$base_css_url = EXAMPLE_URL . 'style/css/base.css';

			if ( is_file( $base_js_dir ) ) {
				wp_register_script( 'base-script', $base_js_url, array( 'jquery' ), false, true );
			}

			if ( is_file( $base_css_dir ) ) {
				wp_enqueue_style( 'base-style', $base_css_url, array() );
			}
		}
	}
}