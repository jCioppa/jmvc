<?php

    namespace App\Models;
    use Iterator;
    use Exception;

    abstract class ModelCollection implements Iterator {

        private $pointer = 0;
        protected $total = 0;
        private $objects = array();

        protected abstract function targetClass();
        protected function notifyAccess(){}

        public function rewind(){
			$this->pointer = 0;
		}

		public function current(){
			return $this->getRow($this->pointer);
		}

		public function key(){
			return $this->pointer;
		}

		public function next(){
			$row = $this->getRow($this->pointer);
			if ($row) $this->pointer++;
			return $row;
		}

		public function valid(){
			return (! is_null($this->current()));
        }

		public function add(Model $object){
			$class = $this->targetClass();
			if (! ($object instanceof $class)) throw new Exception("This is a $class collection");
			$this->notifyAccess();
			$this->objects[$this->total] = $object;
			$this->total++;
        }

		private function getRow($num){
			$this->notifyAccess();
			if ($num >= $this->total || $num < 0) return null;
            if (isset($this->objects[$num])) return $this->objects[$num];
            return null;
		}

        public function first() {
            return isset($this->objects[0]) ? $this->objects[0] : null;
        }

        public function last() {
            return isset($this->objects[$this->total-1]) ? $this->objects[$this->total-1] : null;
        }

    }
