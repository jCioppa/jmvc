<?php

    namespace Commands\Commands;

    use \Commands\Console\InputInterface;
    use \Commands\Console\OutputInterface;
    use \Commands\Input\InputDefinition;
    use \Commands\Input\InputArgument;

    abstract class Command {

        protected $name;
        protected $description;
        protected $definition;
        protected $application;

        public function __construct(InputDefinition $def = null) {
            $this->definition = $def ? $def : new InputDefinition;
            $this->configure();
        }

        public function setApplication(\Commands\Console\Application $application) {
            $this->application = $application;
        }

        public function getApplication() {
            return $this->appliction;
        }

        public function setName($name) {
            $this->name = $name;
            return $this;
        }

        public function getName() {
            return $this->name;
        }

        public function setDescription($desc) {
            $this->description = $desc;
            return $this;
        }

        public function getDescription() {
            return $this->description;
        }

        public function addArgument($name, $mode = null, $description = '', $default = null) {
            $this->definition->addArgument(new InputArgument($name, $mode, $description, $default));
            return $this;
        }
        
        public function addOption($name, $shortcut = null, $mode = null, $description = '', $default = null)
         {
            $this->definition->addOption(new InputOption($name, $shortcut, $mode, $description, $default));
            return $this;
         }
    
        public function setDefinition($definition) {

            if ($definition instanceof InputDefinition) {
                $this->definition = $definition;
            } else {
                $this->definition->setDefinition($definition);
            }

            return $this;
        }


        public function run(InputInterface $input, OuputInterface $output) {
            $input->bind($this->definition);
            $this->initialize($input, $output);
            $input->validate();
            $statusCode = $this->execute($input, $output);
            return is_numeric($statusCode) ? (int) $statusCode : 0;
        }

        public abstract function configure();
        public abstract function execute(InputInterface $input, OutputInterface $output);

    }
