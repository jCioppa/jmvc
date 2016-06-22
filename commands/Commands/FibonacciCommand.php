<?php  

	namespace Commands\Commands;

    use \Commands\Console\InputInterface;
    use \Commands\Console\OutputInterface;
    use \Commands\Input\InputArgument;

	class FibonacciCommand extends Command {

        /* FibonacciCommand 
         *
         * usage : 
         *      phpmaster:fibonacci --start=2 --end=6
         */

        public function configure() {

            $this
                ->setName('phpmaster:fibonacci')
                ->setDescription('display the fibs up to a given number!')
                ->setDefinition(array(
                    new InputOption('start' 's', InputOption::VALUE_OPTIONAL, 'start number'),
                    new InputOption('end', 'e', InputOption::VALUE_REQUIRED, 'end number')
                ));
        }

        public function execute(InputInterface $input, OutputInterface $output){

            $start = intval($input->getOption('start') ? $input->getOption('start') : 1);
            $end = intval($input->getOption('end'));

            for($i = $start; $i < $end; $i++) {
                $output->write(fib($i));
            }

        }

        protected function fib($n) {
            if ($n == 1 || $n == 2) {
                return $n;
            return fib($n-1) + fib($n-2);
        }

	}

?>
