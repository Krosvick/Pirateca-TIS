<?php

namespace Core;

class Container
{
    /**
     * An array that stores the registered instances of objects.
     * The keys are the class names and the values are the instances themselves.
     *
     * @var array
     */
    private $instances = [];

    /**
     * Retrieves an instance of the specified class.
     * If the instance does not exist, it is created using the `make` method.
     *
     * @param string $class The name of the class to retrieve an instance of.
     * @return object The instance of the specified class.
     */
    public function get(string $class): object
    {
        if (!isset($this->instances[$class])) {
            $this->instances[$class] = $this->make($class);
        }

        return $this->instances[$class];
    }

    /**
     * Registers a new instance of a class with a given name.
     * The instance is created using a callable function.
     *
     * @param string $class The name of the class to register an instance of.
     * @param callable $instance The callable function that creates the instance.
     * @return void
     */
    public function set(string $class, callable $instance)
    {
        $this->instances[$class] = $instance($this);
    }

    /**
     * Creates a new instance of the specified class.
     * If the instance does not exist, an exception is thrown.
     *
     * @param string $class The name of the class to create an instance of.
     * @return object The instance of the specified class.
     * @throws \Exception If no instance is registered for the specified class.
     */
    public function make(string $class)
    {
        if (!isset($this->instances[$class])) {
            throw new \Exception("No instance registered for {$class}");
        }

        $callable = $this->instances[$class];
        return $callable($this);
    }
}