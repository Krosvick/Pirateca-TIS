<?php

namespace Core;

class Container
{
    private $instances = [];

    public function get(string $class)
    {
        if (!isset($this->instances[$class])) {
            $this->instances[$class] = $this->make($class);
        }

        return $this->instances[$class];
    }

    public function set(string $class, callable $instance)
    {
        $this->instances[$class] = $instance($this);
    }

    public function make(string $class)
    {
        if (!isset($this->instances[$class])) {
            throw new \Exception("No instance registered for {$class}");
        }

        $callable = $this->instances[$class];
        return $callable($this);
    }
}