<?php 

    namespace Test;

    require_once( '../vendor/autoload.php');
    require_once ('../config/paths.php');
    require_once( '../config/helpers.php' );

    use Support\Traits\Macroable;
    
    class MyMacro {
        use Macroable;
        public function __construct(){}
    }

    class MacroTest extends \PHPUnit_Framework_TestCase {

        public function testMacro() {
            MyMacro::macro("helloworld", function() { echo( "hello world" ); });

            MyMacro::helloworld();
            (new MyMacro)->helloworld();
        }

    }

