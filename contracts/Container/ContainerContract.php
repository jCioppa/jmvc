<?php

namespace Contracts\Container;

interface ContainerContract // IOC Container Interface
{
    public function bind($abstract, $concrete = null, $shared = false);
    public function resolve($contract);
    public function hasBinding($contract);
    public function make($contract);
    public function exec($class, $method, $arguments);
}
