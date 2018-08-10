<?php

namespace appsaloon\model\helper;

/**
 * @codeCoverageIgnore
 */
trait Log {

	/**
	 * Bijhouden van gegevens na een actie
	 *
	 * @param        $message string - mail_verstuurd, mail_gelezen
	 * @param        $date    string - datum uitgevoerd
	 * @param string $by      string - door wie werd de actie uitgevoerd
	 */
	public function log( $message, $date, $by = '' ) {
		if ( ! isset( $this->values['log'] ) || ! is_array( $this->values['log'] ) ) {
			$this->values['log'] = array();
		}

		$this->values['log'][] = array(
			'message' => $message,
			'date'    => $date,
			'by'      => $by,
		);
	}
}