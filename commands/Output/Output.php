<?php

    namespace Commands\Output;

    class Output implements \Commands\Console\OutputInterface {

        public function write($messages, $newline = false, $type = self::OUTPUT_NORMAL) { echo $messages;}
        public function writeln($messages, $type = self::OUTPUT_NORMAL) {echo $messages;}
        public function setVerbosity($level) {}
        public function getVerbosity() {}
        public function setDecorated($decorated) {}
        public function isDecorated() {}

    }
