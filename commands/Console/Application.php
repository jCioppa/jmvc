<?php

    namespace Commands\Console;
    
    use \Commands\Console\InputInterface;
    use \Commands\Console\OutputInterface;
    use \Commands\Input\ArgvInput;
    use \Commands\Output\ConsoleOutput;
    use \Commands\Input\InputOption;
    use \Commands\Input\InputDefinition;
    use \Commands\Input\InputArgument;

    class Application {

        protected $name;
        protected $version;
        protected $defition;
        protected $commands = array();
        protected $runningCommand;
        protected $defaultCommand;
        protected $catchExceptions = false;
        protected $autoExit = true;

        public function __construct($name = "UNKNOWN", $version = "UNKNOWN") {

            $this->name = $name;
            $this->version = $version;

            foreach($this->getDefaultCommands() as $command) {
                $this->add($command);
            }

            $this->definition = $this->getDefaultDefinition();

        }

        public function getDefaultDefinition() {
            return new InputDefinition(array(
                new InputArgument('command', InputArgument::REQUIRED, 'The command to execute'),
                new InputOption('--help', '-h', InputOption::VALUE_NONE, 'Display this help message'),
                new InputOption('--quiet', '-q', InputOption::VALUE_NONE, 'Do not output any message'),
                new InputOption('--verbose', '-v|vv|vvv', InputOption::VALUE_NONE, 'Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug'),
                new InputOption('--version', '-V', InputOption::VALUE_NONE, 'Display this application version'),
                new InputOption('--ansi', '', InputOption::VALUE_NONE, 'Force ANSI output'),
                new InputOption('--no-ansi', '', InputOption::VALUE_NONE, 'Disable ANSI output'),
                new InputOption('--no-interaction', '-n', InputOption::VALUE_NONE, 'Do not ask any interactive question'),
            ));
        }

        public function add($command) {
            array_push($this->commands, $command);
        }

        public function getDefaultCommands() {
            return [];
        }

        public function run(InputInterface $input = null, OutputInterface $output = null) {

            $input = $input ? $input : new ArgvInput();
            $output = $output  ? $output : new ConsoleOutput();
            $this->configureIO($input, $output);

            try {

                $statusCode = $this->doRun($input, $output);

            } catch (\Exception $e) {

                if (! $this->catchExceptions) {
                    throw $e;
                }
    
                $this->renderException($e, $output); 
                $statusCode = $e->getCode();

                if ($this->autoExit) {
                   exit($statusCode); 
                }

            }

            return $statusCode;
    
        }

        public function configureIO(InputInterface $input, OutputInterface $output) {
             
        }

        public function doRun(InputInterface $input, OutputInterface $output) {

            if ($input->hasParameterOption(array("-v", "--version")) == true) {
                $output->write($this->getLongVersion());
                return 0;
            } 

            $name = $this-getCommandName($input);

            if ($input->hasParameterOption(array("--help", "-h"))) {
                if (!$name) {
                    $name = "help";
                    $input = ArrayInput(array('command' => 'help'));
                } else {
                    $this->wantsHelp = true;
                }
            }

            if (!$name) {
                $name = $this->defaultCommand;
                $input = new ArrayInput(array('command' => $this->defaultCommand));
            }

            $command = $this->find($name);
            $this->runningCommand = $command;
            $exitCode = $this->doRunCommand($command, $input, $output);
            $this->runningCommand = null;

            return $exitCode;

        }

        public function doRunCommand($command, $input, $output) {
            $exitCode = $command->run($input, $output);
            return $exitCode; 
        }

        public function getCommandName($input) {
            return $input->getFirstArgument();
        }

        public function renderException(\Exception $e, OutputInterface $output) {

        }

        public function useCommand($command) {
            $this->add($command);
            return $this;
        }

        public function find($name) {
            foreach($this->commands as $command) {
                if ($command->getName() === $name) {
                    return $command;
                }
            }
        }

        public function getName() {
            return $this->name;
        }

        public function getVersion() {
            return $this->version;
        }

        public function getLongVersion()
        {

            if ('UNKNOWN' !== $this->getName() && 'UNKNOWN' !== $this->getVersion()) {
                return sprintf('<info>%s</info> version <comment>%s</comment>', $this->getName(), $this->getVersion());
            }

            return '<info>Console Tool</info>';

        }



    }
