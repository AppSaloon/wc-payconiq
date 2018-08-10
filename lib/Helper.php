<?php

namespace appsaloon\lib;

class Helper {

	/**
	 * @param $array    array   lijst met alle soorten variabels
	 *
	 * @return array    array   lijst met alleen integer variabels
	 */
	public static function only_integer_allowed( $array ) {
		return array_filter( $array, function ( $a ) {
			return is_int( $a );
		} );
	}
}