<?php

    namespace Commands\Input;

    class InputOption {

        const VALUE_NONE = 1; 
        const VALUE_REQUIRED = 2; 
        const VALUE_OPTIONAL = 4;
        const VALUE_IS_ARRAY = 8;

        private $name;
        private $shortcut;
        private $mode;
        private $description;
        private $default;

        public function __construct($name, $shortcut = null, $mode = null, $description = '', $default = null)
        {

            if (0 === strpos($name, '--')) {
                $name = substr($name, 2);
            }

            if (empty($name)) {
                throw new \InvalidArgumentException('An option name cannot be empty.');
            }

            if (empty($shortcut)) {
                $shortcut = null;
            }

            if (null !== $shortcut) {

                if (is_array($shortcut)) {
                    $shortcut = implode('|', $shortcut);
                }

                $shortcuts = preg_split('{(\|)-?}', ltrim($shortcut, '-'));
                $shortcuts = array_filter($shortcuts);
                $shortcut = implode('|', $shortcuts);

                if (empty($shortcut)) {
                    throw new \InvalidArgumentException('An option shortcut cannot be empty.');
                }
            }

            if (null === $mode) {
                $mode = self::VALUE_NONE;
            } elseif (!is_int($mode) || $mode > 15 || $mode < 1) {
                throw new \InvalidArgumentException(sprintf('Option mode "%s" is not valid.', $mode));
            }

            $this->name = $name;
            $this->shortcut = $shortcut;
            $this->mode = $mode;
            $this->description = $description;

            if ($this->isArray() && !$this->acceptValue()) {
                throw new \InvalidArgumentException('Impossible to have an option mode VALUE_IS_ARRAY if the option does not accept a value.');
            }

            $this->setDefault($default);

        }

        public function isArray() {
            return false;
        }

        public function setDefault($default) {
            $this->default = $default;
        }
    
        public function getName() {
            return $this->name;
        }

        public function getShortcut() {
            return $this->shortcut;
        }
     

    }
