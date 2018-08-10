<?php

namespace appsaloon\model\helper;

use appsaloon\lib\Helper;

/**
 * @codeCoverageIgnore
 */
trait Save {
	
	protected $meta_fields = array();
	
	protected $post_fields = array();
	
	protected $tax_fields = array();

	protected $only_post_meta_update;

	/**
	 * Opslaan van alle gegevens
	 * - opslitsen van variabels in 2 delen:
	 * post fields of meta fields
	 */
	public function save() {
		$allow_post_fields_only = array( 'post_title' );

		foreach ( $this->values as $key => $value ) {
			if ( in_array( $key, $this->default_post_fields ) ) {
				if ( in_array( $key, $allow_post_fields_only ) ) {
					$this->post_fields[ $key ] = $value;
				}
			} else if ( $key == 'tax_input' ) {
				$this->tax_fields = $value;
			} else {
				$this->meta_fields[ $key ] = $value;
			}
		}

		if ( ! $this->only_post_meta_update ) {
			$this->save_post_fields();
			$this->save_tax_fields();
		}

		$this->save_meta_fields();
	}

	/**
	 * Opslaan van post velden
	 *
	 * TODO Alle velden updaten
	 */
	public function save_post_fields() {
		$query = "UPDATE " . $this->wpdb->posts . "
					SET post_title = '" . $this->post_title . "',
						post_status = '" . $this->post_status . "'
					WHERE ID = " . $this->ID;
		$this->wpdb->query( $query );
	}

	/**
	 * Opslaan van taxonomy velden
	 *
	 * Alleen integer of leeg waarde als terms toelaten
	 */
	public function save_tax_fields() {
		if ( is_array( $this->tax_fields ) && sizeof( $this->tax_fields ) != 0 ) {
			foreach ( $this->tax_fields as $taxonomy => $terms ) {
				if ( is_array( $terms ) ) {
					$terms = Helper::only_integer_allowed( $terms );
				}

				wp_set_object_terms( $this->ID, $terms, $taxonomy );
			}
		}
	}

	/**
	 * Opslaan van meta velden
	 */
	protected function save_meta_fields() {
		// save meta fields
		foreach ( $this->meta_fields as $key => $value ) {
			update_post_meta( $this->ID, $key, $value );
		}
	}

	/**
	 * Updaten van velden
	 *
	 * $data = meta_key => meta_value
	 *
	 * @param $data                 array   lijst van post, postmeta en terms.
	 * @param $meta_data_only            boolean True alleen post meta updaten
	 *                              False alles updaten
	 *
	 * @return $this model terug gegeven
	 */
	public function update( $data, $meta_data_only = false ) {
		$this->only_post_meta_update = $meta_data_only;

		foreach ( $data as $key => $value ) {
			$this->{$key} = $value;
		}

		$this->save();

		return $this;
	}

	/**
	 * Aanmaken van nieuw post
	 * Wordt alleen gebruikt bij scripts!
	 *
	 * Bijvoorbeeld:
	 * Offerte -> polis
	 *
	 * @param $post_type    string nieuw post type
	 *
	 * @return $this    class geeft deze model terug
	 */
	public function create( $post_type, $post_title ) {
		$args                = array();
		$args['post_title']  = $this->post_title = $post_title;
		$args['post_parent'] = $this->ID;
		$args['post_type']   = $post_type;
		$args['post_status'] = $this->post_status = 'publish';

		$this->ID = wp_insert_post( $args );

		$this->save();

		return $this;
	}
}