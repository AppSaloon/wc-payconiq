<?php

namespace Facebook\WebDriver;
/**
 * Deze test is enkel gemaakt als onderzoek naar php-webdriver.
 * De testen doen niks nuttig, buiten bewijzen dat ze werken.
 * Deze pagina kan gebruikt worden als basis voor verder onderzoek.
 */

// http://codeception.com/11-12-2013/working-with-phpunit-and-selenium-webdriver.html
// https://github.com/facebook/php-webdriver/blob/community/example.php
// https://testingbot.com/support/getting-started/phpunit.html
// https://www.gridlastic.com/php-webdriver-example.html
// https://www.linkedin.com/pulse/automation-selenium-phpunit-webdriver-eudes-costa-1

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;

// require_once( '/home/vagrant/vendor/autoload.php' );

/**
 * @group boilerplate
 * @group acceptance
 */
class Test_Webdriver extends \PHPUnit_Framework_TestCase {

    /**
     * @var RemoteWebDriver
     */
    protected $webDriver;

    public function setUp() {

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

        $this->webDriver = RemoteWebDriver::create( 'http://localhost:4444/wd/hub', DesiredCapabilities::firefox() );
    }

    public function tearDown() {
        $this->webDriver->quit();
    }

    public function test_ValidFormSubmission() {
        $this->webDriver->get( 'https://www.google.be' );
        // $content = $this->webDriver->findElement( WebDriverBy::tagName('body') )->getText();
        // fotoke trekken kan ook
        // $this->webDriver->takeScreenshot( "screenshot.jpg");
        $this->assertEquals( 'Google', $this->webDriver->getTitle() );
    }
}