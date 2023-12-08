<?php

namespace Core;

class Session
{
    protected const FLASH_KEY = 'flash_messages';

  
    /**
     * Session constructor.
     *
     * Initializes the session and sets the 'remove' flag of all flash messages to true.
     */
    public function __construct()
    {
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];
        foreach ($flashMessages as $key => &$flashMessage) {
            $flashMessage['remove'] = true;
        }
        $_SESSION[self::FLASH_KEY] = $flashMessages;
    }
    

    /**
     * Sets a flash message.
     *
     * @param string $key The key used to identify the flash message.
     * @param mixed $message The value of the flash message.
     */
    public function setFlash($key, $message)
    {
        $_SESSION[self::FLASH_KEY][$key] = [
            'remove' => false,
            'value' => $message
        ];
    }

    /**
     * Retrieves the value of a flash message.
     *
     * @param string $key The key used to identify the flash message.
     * @return mixed The value of the flash message, or false if it does not exist.
     */
    public function getFlash($key)
    {
        return $_SESSION[self::FLASH_KEY][$key]['value'] ?? false;
    }

    /**
     * Sets a session variable.
     *
     * @param string $key The key used to identify the session variable.
     * @param mixed $value The value of the session variable.
     */
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Retrieves the value of a session variable.
     *
     * @param string $key The key used to identify the session variable.
     * @return mixed The value of the session variable, or false if it does not exist.
     */
    public function get($key)
    {
        return $_SESSION[$key] ?? false;
    }

    /**
     * Removes a session variable.
     *
     * @param string $key The key used to identify the session variable.
     */
    public function remove($key)
    {
        unset($_SESSION[$key]);
    }

    /**
     * Destructor method that removes flash messages before the session is destroyed.
     */
    public function __destruct()
    {
        $this->removeFlashMessages();
    }

    /**
     * Removes flash messages with the 'remove' flag set to true.
     */
    private function removeFlashMessages()
    {
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];
        foreach ($flashMessages as $key => $flashMessage) {
            if ($flashMessage['remove']) {
                unset($flashMessages[$key]);
            }
        }
        $_SESSION[self::FLASH_KEY] = $flashMessages;
    }
}