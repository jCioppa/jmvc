<?php
    namespace App\Container;
    use Closure;

    class Container implements \ArrayAccess, \Contracts\Container\ContainerContract {
        
        protected $bindings;
        protected $instances;
        protected $resolved;
        protected $aliases;

// array access implementation

        public function offsetExists($offset) {
            return isset($this->bindings[$offset]);
        }

        public function offsetGet($offset) {
            return $this->make($offset);
        }

        public function offsetSet($offset, $value) {

            if (! $value instanceof Closure) {
                $value = function () use ($value) {
                    return $value;
                };
            }

            $this->bind($offset, $value);
        }

        public function offsetUnset($offset) {
            unset($this->bindings[$offset], $this->instances[$offset], $this->resolved[$offset]); 
        }

        public function __get($key) {
            return $this[$key];
        }

        public function __set($key, $val) {
            $this[$key] = $val;
        }

// container implementation

        public function bind($abstract, $concrete = null, $shared = false) {
            if ($concrete instanceof \Closure) 
                $this->bindings[$abstract] = $concrete;
            else 
                $this->bindings[$abstract] = function() use ($concrete) {
                    return $concrete; 
                };
        }

        public function resolve($contract) {
            return $this->make($contract);
        }

        public function hasBinding($name) {
            return isset($this->bindings[$name]) || isset($this->instances[$name]) || isset($this->aliases[$name]);
        }

        public function hasBeenResolved($name) {
            return isset($this->instances[$name]) || isset($this->resolved[$name]);
        }

        public function make($name) {
            return $this->bindings[$name]();
        }

        public function exec($class,$method, $arguments) {

            $className = get_class($class);
            $r = new \ReflectionMethod($className, $method);
            $params = $r->getParameters();
            $args = array();

            foreach($params as $param) {
                $typeHint = $param->getClass();
                if($typeHint) $typeHint = $typeHint->name;

                if ($this->hasBinding($typeHint)) {
                    $arg = $this->resolve($typeHint);
                    array_push($args, $arg);
                } 
            }
            
            $ret = call_user_func_array(array($class, $method), array_merge($args, $arguments)); 
            return $ret;
        }



    
    }
