<?php

namespace appsaloon\tests\unit;

use appsaloon\tests\unit\fixtures\Structures;

/**
 * Sample test case.
 */
class Fixture extends \WP_UnitTestCase {

	/**
     * Database terug inladen door sql queries uit te voeren via exec commando.
     */
    public function setUp() {
        exec( 'mysql -u ' . $GLOBALS['DB_USER'] . ' -p' . $GLOBALS['DB_PASSWD'] . ' ' . $GLOBALS['DB_DBNAME'] . ' < ' . __DIR__ . '/fixtures/tables.sql' );
        parent::setUp();
    }

    public function test_siteurl() {
        $this->assertEquals( 'http://example.org', get_option( 'siteurl', true ) );
    }

}