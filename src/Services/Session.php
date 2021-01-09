<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Users;

/**
 * Class Session
 * @package App\Services
 */
class Session
{
    
    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key)
    {
        if($this->has($key)) {
            return $_SESSION[$key];
        }
    }
    
    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key) : bool
    {
        return isset($_SESSION[$key]);
    }
    
    /**
     * @return array
     */
    public function getAll() : array
    {
        return $_SESSION;
    }
    
    /**
     * @param string $key
     * @param $value
     */
    public function set(string $key, $value) : void
    {
        $_SESSION[$key] = $value;
    }
    
    /**
     * @return Users|null
     */
    public function getUser() : ?Users
    {
        return $_SESSION['user'] ?? null;
    }
    
    public function remove($key) : void
    {
        if($this->has($key)) {
            unset($_SESSION[$key]);
        }
    }
}