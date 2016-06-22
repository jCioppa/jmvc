<?php 

    namespace Test;

    require_once( '../vendor/autoload.php');
    require_once ('../config/paths.php');
    require_once( '../config/helpers.php' );

    use Commands\Console\Application;


    class CommandTest extends \PHPUnit_Framework_TestCase {

        public function testApplication() {
            $application = new Application("My Application", "1");
            $this->assertEquals("My Application", $application->getName()); 
            $this->assertEquals("1", $application->getVersion());
        }

        public function testCommand() {
            $application = new Application("My app", "0.0.1");
        }

    }
