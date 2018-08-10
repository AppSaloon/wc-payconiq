<?php

namespace appsaloon\model;

class Example_Model extends Base_Model {

	public function __construct() {
		parent::__construct();
	}

	/**
	 * Voorbeeld methode om dynamische set methode te overschrijven voor een bepaalde meta key
	 *
	 * information_by is de meta key
     *
     * @codeCoverageIgnore
	 */
	public function set_information_by( $key, $value ) {
		if ( $value == 'test' ) {
			$this->values[ $key ] = '';
		} else {
			$this->values[ $key ] = $value;
		}
		return true;
	}
}