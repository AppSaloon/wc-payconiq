<?php

namespace appsaloon\controller;

use appsaloon\model\Base_Model;

Interface Controller_Interface {

	/**
	 * @param $model \appsaloon\model\Base_Model
	 * @param $post_id
	 * @param $meta_data_only  boolean True indien actie komt via
	 *                         edit post (admin) False default
	 *
	 */
	public function save( Base_Model $model, $post_id, $meta_data_only = false );

	/**
	 * Extra action after POST save
	 *
	 * @return mixed
	 */
	public function after_save();

	/**
	 * Load POST
	 */
	public function load_post( $post_id );
}