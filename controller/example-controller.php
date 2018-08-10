<?php

namespace appsaloon\controller;

use appsaloon\lib\Container;

class Example_Controller extends Base_Controller implements Controller_Interface {

	public function after_save() {
		parent::after_save();

		//acties die je wilt uitvoeren na opslaan van model
		//bijvoorbeeld mailen
	}

	/**
	 * Inladen van information metabox
	 *
	 * @throws \DI\DependencyException
	 * @throws \DI\NotFoundException
	 */
	public function show_information_metabox() {
		$this->model = Container::getInstance()->container->get('example_model');
		$this->load_post( $_GET['post'] );

		include_once EXAMPLE_DIR . '/view/admin/information.php';
	}
}