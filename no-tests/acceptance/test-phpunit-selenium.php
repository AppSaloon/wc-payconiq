<?php

namespace appsaloon\tests\acceptance;

/**
 * Deze test is enkel gemaakt als onderzoek naar phpunit-selenium.
 * De testen doen niks nuttig, buiten bewijzen dat ze werken.
 * Deze pagina kan gebruikt worden als basis voor verder onderzoek.
 *
 * @group acceptance
 * links naar voorbeelden
 * https://github.com/giorgiosironi/phpunit-selenium/blob/master/Tests/Selenium2TestCaseTest.php
 * https://github.com/Vinai/phpunit-selenium-example/blob/master/tests/SeleniumExampleTest.php
 * https://www.sitepoint.com/using-selenium-with-phpunit/
 * https://code.tutsplus.com/tutorials/how-to-use-selenium-2-with-phpunit--net-27577
 * https://www.slideshare.net/ptahdunbar/automated-testing-in-wordpress-really
 */
// https://github.com/beberlei/bankaccount
// https://www.sitepoint.com/using-the-selenium-web-driver-api-with-phpunit/
// https://gist.github.com/curtismcmullan/7be1a8c1c841a9d8db2c
// https://github.com/seanbuscay/vagrant-phpunit-selenium/blob/master/setup.sh
// https://phpunit.de/manual/3.7/en/selenium.html#selenium.selenium-rc
// https://www.sitepoint.com/using-selenium-with-phpunit/
// https://code.google.com/archive/p/php-webdriver-bindings/downloads
// https://github.com/Vinai/phpunit-selenium-example/blob/master/tests/SeleniumExampleTest.php
// https://github.com/giorgiosironi/phpunit-selenium/blob/master/Tests/Selenium2TestCaseTest.php
// https://code.tutsplus.com/tutorials/how-to-use-selenium-2-with-phpunit--net-27577

/**
 * @group boilerplate
 * @group acceptance
 */
class Test_Phpunit_Selenium extends \PHPUnit_Extensions_Selenium2TestCase {

    // werkt bij mij niet
    // protected $captureScreenshotOnFailure = TRUE;
    // protected $screenshotPath = '/vagrant';
    // protected $screenshotUrl = 'http://localhost/screenshots';

    protected function setUp() {

        // check of selenium draait, anders skippen we
        $selenium_running = false;
        $fp = @fsockopen( 'localhost', 4444 );
        if ( $fp !== false ) {
            $selenium_running = true;
            fclose( $fp );
        }
        if ( false === $selenium_running ) {
            $this->markTestSkipped( 'Alle testen skippen omdat selenium server niet actief is!' );
        }

        // $this->setHost('localhost');
        // $this->setPort(4444);
        $this->setBrowser( 'firefox' );
        $this->setBrowserUrl( 'http://localhost:4444/wd/hub' );

        // $this->setBrowser( 'firefox' );
        // $this->setBrowser( 'chrome' );
        // $this->setBrowserUrl( 'https://www.google.be/' );
        // var_dump( $this );
    }

    public function tearDown() {
        $this->stop();
    }

    public function test_title() {
        $this->url( 'https://www.google.be' );
        // fotoke trekken kan ook
        // file_put_contents( 'screenshot1.jpg', $this->currentScreenshot() );
        $this->assertEquals( 'Google', $this->title() );
        // $element = $this->byTag( 'p' );
        // $this->assertContains( 'google', $element->text() );
    }

}