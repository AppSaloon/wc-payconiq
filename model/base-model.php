<?php

namespace appsaloon\model;

use appsaloon\model\helper\Find;
use appsaloon\model\helper\Log;
use appsaloon\model\helper\Save;

abstract class Base_Model implements Model_Interface {

	use Find, Save, Log;

	/**
	 * Default post velden
	 */
	public $default_post_fields = array(
		'ID',
		'post_author',
		'post_date',
		'post_date_gmt',
		'post_content',
		'post_title',
		'post_excerpt',
		'post_status',
		'comment_status',
		'ping_status',
		'post_password',
		'post_name',
		'to_ping',
		'pinged',
		'post_modified',
		'post_modified_gmt',
		'post_content_filtered',
		'post_parent',
		'guid',
		'menu_order',
		'post_type',
		'post_mime_type',
		'comment_count'
	);

	/**
	 * Velden die niet toegevoegd mogen worden
	 *
	 * @var array
	 */
	protected $not_fillable_meta_fields = array(
		'_wpnonce',
		'_wp_http_referer',
		'user_ID',
		'action',
		'originalaction',
		'original_post_status',
		'referredby',
		'_wp_original_http_referer',
		'post_ID',
		'meta-box-order-nonce',
		'closedpostboxesnonce',
		'samplepermalinknonce',
		'hidden_post_status',
		'hidden_post_password',
		'hidden_post_visibility',
		'visibility',
		'mm',
		'jj',
		'aa',
		'hh',
		'mn',
		'ss',
		'hidden_mm',
		'cur_mm',
		'hidden_jj',
		'cur_jj',
		'hidden_aa',
		'cur_aa',
		'hidden_hh',
		'cur_hh',
		'hidden_mn',
		'cur_mn',
		'original_publish',
		'_edit_last',
		'_edit_lock',
		'data',
		'interval',
		'_nonce',
		'screen_id',
		'has_focus',
		'auto_draft',
		'save'
	);

	/**
	 * Object variabels worden hierin opgeslagen
	 *
	 * @var array
	 */
	protected $values = array();

	/**
	 * Database connectie
	 * @var \wpdb
	 */
	protected $wpdb;

	public function __construct() {
		global $wpdb;

		$this->wpdb = $wpdb;
	}

	/**
	 * Deze functie update post veld in posts of postmeta tabel
	 *
	 * Bijvoorbeeld:
	 * $offerte = new Custom_Post_Type();
	 * $offerte->find_by_id(1); <-- is een trait(find)
	 * $offerte->post_title = 'nieuw titel'; <-- deze methode zorgt voor die lijn
	 * $offerte->save();    <-- is een trait(save)
	 *
	 * @param $key
	 * @param $value
     * @codeCoverageIgnore
	 */
	public function __set( $key, $value ) {
		if ( ! in_array( $key, $this->not_fillable_meta_fields ) ) {
			$method = 'set_' . $key;

			if ( method_exists( $this, $method ) ) {
				$this->{$method}( $key, $value );
			} else {
				$this->values[ $key ] = $value;
			}
		}
	}

	/**
	 * Deze functie geeft een specifiek veld van een post terug.
	 *
	 * Mogelijke velden komen van volgende database tabellen:
	 * - posts
	 * - postmeta
	 *
	 * Je kan deze data ophalen via:
	 * $offerte = new Custom_Post_Type();
	 * echo $offerte->post_title; -> van posts tabel
	 * of
	 * echo $offerte->referentie; -> van postmeta tabel
	 *
	 * @param $key
	 *
	 * @return  boolean indien niet gevonden
	 *          string  indien waarde gevonden in values variabel
	 *          method  indien custom methode gevonden
     * @codeCoverageIgnore
     */
	public function __get( $key ) {
		$method = 'get_' . $key;

		if ( method_exists( $this, $method ) ) {
			$this->{$method}();
		} else {
			return isset( $this->values[ $key ] ) ? $this->values[ $key ] : false;
		}
	}
}