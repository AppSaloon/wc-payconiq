<?php

namespace wc_payconiq\lib;

Interface Container_Interface {

	public function build_container();

	/**
	 * Set classes that needs to be used
	 */
	public function set_classes();
}