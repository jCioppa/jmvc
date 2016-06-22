<?php

    namespace Commands\Input;

    use \Commands\Input\InputDefinition;

    class Input implements \Commands\Console\InputInterface {
    
        protected $definition;
        protected $options = [];
        protected $arguments = [];
        protected $interactive = true;

        public function getFirstArgument() {}
        public function hasParameterOption($values) {}
        public function getParameterOption($values, $default = false) {}

        public function bind(InputDefinition $definition) {
            $this->arguments = array();
            $this->options = array();
            $this->definition = $definition;
            $this->parse();
        }

        public function parse() {

        }

        public function validate() {}
        public function getArguments() {}
        public function getArgument($name) {}
        public function setArgument($name, $value) {}
        public function hasArgument($name) {}
        public function getOptions() {}
        public function getOption($name) {}
        public function setOption($name, $value) {}
        public function hasOption($name) {}

    }
