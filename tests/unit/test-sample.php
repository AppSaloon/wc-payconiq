<?php

namespace appsaloon\tests\unit;

/**
 * Class SampleTest
 *
 * @package Klasse_Plugin_Boilerplate
 */
use appsaloon\lib\Container;

/**
 * Sample test case.
 *
 * @group boilerplate
 * @group unit
 */
class SampleTest extends Fixture {

	public $test_obj;

	public function setUp() {
		Parent::setUp();

		$this->test_obj = Container::getInstance()->container->get( 'example_model' );
	}

	/**
	 * @group unit
	 */
	function test_set_information_by() {
		$this->assertTrue( $this->test_obj->set_information_by( 'test_key', 'test_value' ) );

		$this->assertContains( 'test_value', $this->test_obj->test_key );
	}

	/**
	 * @group unit
	 */
	function test_should_return_empty_when_the_value_is_test() {
		$this->assertTrue( $this->test_obj->set_information_by( 'test_key', 'test' ) );

		$this->assertEmpty( $this->test_obj->test_key );
	}

	/**
	 * Aantal posts optellen
	 *
	 * Geeft 0 terug omdat alleen klasse-abonnees toegevoegd zijn
	 * @group unit
	 */
	function test_aantal_posts() {
		$amount = wp_count_posts();

		$this->assertEquals( 0, $amount->publish );
	}
}
