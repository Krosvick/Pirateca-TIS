<?php


namespace Core\Auth;
use DAO\UsersDAO;

class User implements UserInterface
{

    /**
     * @var int
     */
    private $userId;
    /**
     * @var string
     */
    private $userName;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $roles;

    /**
     * @var bool
     */
    private $enabled = true;

    /**
     * @return null|int
     */
    public static function getPrimaryKey(): string
    {
        return 'id';
    }
    public static function findOne(int $id): ?User
    {
        $userDAO = new UsersDAO();
        $user = $userDAO->find($id, self::class);
        if($user){
            return $user;
        }
        return null;
    }
    /**
     * @return null|string
     */
    public function getUsername(): ?string
    {
        return $this->userName;
    }

    /**
     * @return null|string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getRoles(): string
    {
        return $this->roles;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param string $userName
     * @return User
     */
    public function setUserName(string $userName): self
    {
        $this->userName = $userName;
        return $this;
    }

    /**
     * @param string $password
     * @return User
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @param array $roles
     * @return User
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @param bool $enabled
     * @return User
     */
    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;
        return $this;
    }
}