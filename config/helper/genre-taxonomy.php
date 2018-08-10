<?php

namespace appsaloon\config\helper;

Trait Genre_Taxonomy {

	public function add_genre_taxonomy() {
		add_action( 'init', array( $this, 'create_genre_taxonomy' ) );
	}

	public function create_genre_taxonomy() {
		register_taxonomy(
			'genre',
			static::POST_TYPE,
			array(
				'label'             => __( 'Genre' ),
				'public'            => true,
				'show_ui'           => true,
				'show_admin_column' => true,
				'rewrite'           => false,
				'hierarchical'      => true,
			)
		);

		register_taxonomy(
			'fase',
			static::POST_TYPE,
			array(
				'label'             => __( 'Fase' ),
				'public'            => true,
				'show_ui'           => true,
				'show_admin_column' => true,
				'rewrite'           => false,
				'hierarchical'      => false,
			)
		);
	}
}