<?php

namespace appsaloon\model;

Interface Model_Interface {

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
	public function __set( $key, $value );

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
	public function __get( $key );
}