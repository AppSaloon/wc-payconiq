<?php

namespace appsaloon\config\metabox;

Trait Information {

	public function add_information_meta_box() {
		add_action( 'add_meta_boxes', array( $this, 'information_box' ) );
	}

	public function information_box() {
		add_meta_box(
			'information',
			'Information',
			array(
				$this->controller,
				'show_information_metabox'
			),
			static::POST_TYPE,
			'advanced', 'high'
		);
	}
}