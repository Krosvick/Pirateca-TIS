<?php


namespace Core\Auth\Token;


use Core\Auth\UserInterface;

/**
 * Class UserToken
 * @package Core\Auth\Token
 */
class UserToken implements UserTokenInterface
{
    /**
     * @var UserInterface
     */
    private $user;

    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function serialize(): string
    {
        return serialize($this);
    }
}