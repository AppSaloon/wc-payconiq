<?php

namespace appsaloon\controller;

use appsaloon\model\Base_Model;

class Base_Controller {

	/**
	 * @var \appsaloon\model\Base_Model
	 */
	public $model;

	/**
	 * @param \appsaloon\model\Base_Model $model
	 * @param                                                    $post_id
	 * @param                                                    $meta_data_only  boolean True indien actie komt via
	 *                                                                            edit post (admin) False default
	 *
	 */
	public function save( Base_Model $model, $post_id, $meta_data_only = false ) {
		//TODO validate and sanitize $_POST data
		$data        = $_POST;
		$this->model = $model->find_by_id( $post_id )->update( $data, $meta_data_only );

		$this->after_save();
	}

	/**
	 * Extra action after POST save
	 *
	 * @return mixed
	 */
	public function after_save() {
	}

	/**
	 * Post inladen
	 */
	public function load_post( $post_id ) {
		if ( $this->model->ID == false ) {
			$this->model->find_by_id( $post_id );
		}
	}
}