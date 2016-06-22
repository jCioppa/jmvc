<?php

    namespace Commands\Console;
    use \Commands\Input\InputDefinition;

    interface InputInterface {
        
        public function getFirstArgument(); // first arg from raw param list
        public function hasParameterOption($values); // true if raw params contain a value
        public function getParameterOption($values, $default = false); // value of raw option
        public function bind(InputDefinition $definition);
        public function validate(); // validate if arguments given are correct
        public function getArguments(); // returns all args merged with default values
        public function getArgument($name);
        public function setArgument($name, $value);
        public function hasArgument($name);
        public function getOptions();
        public function getOption($name);
        public function setOption($name, $value);
        public function hasOption($name);

    }
