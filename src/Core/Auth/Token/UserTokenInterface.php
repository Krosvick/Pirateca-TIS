<?php

namespace Core\Auth\Token;

use Core\Auth\UserInterface;

/**
 * Interface UserTokenInterface
 * @package Core\Auth\Token
 */
interface UserTokenInterface
{
    const DEFAULT_PREFIX_KEY = 'user_security';

    public function getUser(): UserInterface;

    public function serialize(): string;
}