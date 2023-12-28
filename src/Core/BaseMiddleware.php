<?php

namespace Core;

/**
 * Abstract class representing a base middleware.
 *
 * This class defines a base middleware with a single abstract method `execute()`.
 * Any class that extends `BaseMiddleware` must provide an implementation for the `execute()` method.
 *
 * Example Usage:
 * ```php
 * abstract class MyMiddleware extends BaseMiddleware {
 *     public function execute() {
 *         // implementation of the execute method
 *     }
 * }
 * ```
 */
abstract class BaseMiddleware{

    abstract public function execute();
}