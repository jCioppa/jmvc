<?php  

	namespace Commands\Commands;

    use \Commands\Console\InputInterface;
    use \Commands\Console\OutputInterface;
    use \Commands\Input\InputArgument;

	class GreetCommand extends Command {

        public function configure() {

            $this
                ->setName('phpmaster:greet')
                ->setDescription('say a greeting!')
                ->setDefinition(array(
                    new InputArgument('name', InputArgument::OPTIONAL, 'who do you want to greet?'),
                    new InputOption('yell', 'y', InputOption::VALUE_NONE, 'yell your greeting')
                ));
        }

        public function execute(InputInterface $input, OutputInterface $output){

            $name = $input->getArgument('name');

            if ($name) {
                $text = "hello " . $name;
            else {
                $text = "hello world";
            }

            if ($input->getOption("yell")) {
                $text = strtoupper($text);
            }

            $ouput->writeln($text);
        }

	}

?>
