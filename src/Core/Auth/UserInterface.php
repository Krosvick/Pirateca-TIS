<?php

namespace Core\Auth;

/**
 * Interface UserInterface
 * @package Core\Auth
 */
interface UserInterface
{
    public function getUsername() :?string;

    public function getPassword() :?string;

    public function getRoles() : string;

    public function isEnabled(): bool;
}