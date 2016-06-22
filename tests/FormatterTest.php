<?php 

    namespace Test;

    require_once( 'vendor/autoload.php');
    require_once ('config/paths.php');
    require_once( 'config/helpers.php' );

    use Logger\Formatter\JsonFormatter;
    
    class FormatterTest extends \PHPUnit_Framework_TestCase {

        static $record = [['h\nello' => 'world'], ['boobs', 12]];

        public function testJsonFormatter() {
            $formatter = new JsonFormatter;
            $this->assertEquals($formatter->getBatchMode(), JsonFormatter::BATCH_MODE_JSON);       
            $this->assertEquals($formatter->isAppendingNewLines(), true);
        }

        public function testBatchModeJsonAppendNewLines() {
            $formatter = new JsonFormatter(1,true);
            $formatted = $formatter->formatBatch(self::$record);
            print_r($formatted);
        }

        public function testBatchModeNewLinesAppendNewLines() {
            $formatter = new JsonFormatter(2,true);
            $formatted = $formatter->formatBatch(self::$record);
            print_r($formatted);
        }


        public function testBatchModeJsonNoNewLines() {
            $formatter = new JsonFormatter(1,false);
            $formatted = $formatter->formatBatch(self::$record);
            print_r($formatted);
        }

        
        public function testBatchModeNewLinesDontAppendNewLines() {
            $formatter = new JsonFormatter(2,false);
            $formatted = $formatter->formatBatch(self::$record);
            print_r($formatted);
        }





    }

