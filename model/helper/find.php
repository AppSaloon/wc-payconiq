<?php

namespace appsaloon\model\helper;

/**
 * @codeCoverageIgnore
 */
trait Find {

	/**
	 * Zoek door ID
	 *
	 * @param $id
	 *
	 * @return $this class
	 */
	public function find_by_id( $id ) {
		// haal meta velden op indien post bestaat
		return $this->get_post_by_id( $id );
	}

	/**
	 * Zoeken naar voordelen aan de hand van message id
	 * message id is een meta key
	 *
	 * @param $id
	 *
	 * TODO deze methode wordt niet gebruikt bij klasse, maar is een voorbeeld functie voor in de toekomst.
	 */
	public function find_by_message_id( $id ) {
		$this->get_postmeta_by_meta_key_and_value( 'email_message_id', $id );
	}

	/**
	 * Ophalen van de post
	 *
	 * @param $id
	 *
	 * @return mixed false indien niet gevonden
	 *               array indien gevonden
	 */
	private function get_post_by_id( $id ) {
		$query = "SELECT * FROM " . $this->wpdb->posts . "
					WHERE ID = " . $id;

		$result = $this->wpdb->get_results( $query );

		if ( is_array( $result ) && sizeof( $result ) == 1 ) {
			$result = current( $result );

			// sla alle gegevens op in deze model
			$this->process_post_result( $result );

			// haal post meta velden op
			$post_meta = get_post_meta( $id );

			// metavelden opslaan
			$this->process_postmeta_result( $post_meta );

			// taxinput ophalen
			$this->process_taxonomy_result( $id );

			return $this;
		}

		return false;
	}

	/**
	 * Haal custom post types op aan de hand van meta veld
	 *
	 * @param $meta_key
	 * @param $meta_value
	 *
	 * @return bool false indien niet gevonden of meer dan 1 keer gevonden
	 */
	private function get_postmeta_by_meta_key_and_value( $meta_key, $meta_value ) {
		$query = "SELECT ID
                    FROM " . $this->wpdb->posts . "
                    RIGHT JOIN " . $this->wpdb->postmeta . " ON post_id = ID
                    WHERE meta_key = '" . $meta_key . "'
                    AND meta_value = '" . $meta_value . "'";

		$result = $this->wpdb->get_results( $query );

		if ( is_array( $result ) && sizeof( $result ) == 1 ) {
			$post = current( $result );

			return $this->get_post_by_id( $post->ID );
		}

		return false;
	}

	/**
	 * Steekt alle post gegevens in deze class
	 *
	 * @param $result array gegevens van posts tabel
	 */
	private function process_post_result( $result ) {
		foreach ( $result as $key => $value ) {
			$this->{$key} = $value;
		}
	}

	/**
	 * Haalt alle meta velden op
	 *
	 * @param $result
	 */
	private function process_postmeta_result( $result ) {
		if ( is_array( $result ) ) {
			foreach ( $result as $meta_key => $meta_value ) {
				$this->{$meta_key} = maybe_unserialize( maybe_unserialize( current( $meta_value ) ) );
			}
		}
	}

	/**
	 * @param $id
	 */
	private function process_taxonomy_result( $id ) {
		$result = $this->get_post_taxonomies( $id );

		if ( is_array( $result ) && sizeof( $result ) != 0 ) {
			$this->tax_input = array();
			foreach ( $result as $term ) {
				$this->values['tax_input'][ $term->taxonomy ][] = (int) $term->term_id;
			}
		}
	}

	/**
	 * @param $id
	 */
	private function get_post_taxonomies( $id ) {
		$query = 'SELECT taxonomy.term_id as term_id, taxonomy.taxonomy as taxonomy
			FROM ' . $this->wpdb->term_relationships . ' as relation
			INNER JOIN ' . $this->wpdb->term_taxonomy . ' as taxonomy
			ON relation.term_taxonomy_id = taxonomy.term_taxonomy_id
			WHERE object_id = ' . $id;

		return $this->wpdb->get_results( $query );
	}
}