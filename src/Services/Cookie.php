<?php
declare(strict_types=1);

namespace App\Services;

class Cookie
{
    public function set(string $name, $content, ?int $expire = null, string $path = '/')
    {
        setcookie($name, $content, $expire, $path);
    }
    
    public function has(string $key) : bool
    {
        return isset($_COOKIE[$key]);
    }
    
    public function get(string $key)
    {
        if($this->has($key)) {
            return $_COOKIE[$key];
        }
    }
    
    public function remove(string $key)
    {
        if($this->has($key)) {
            unset($_COOKIE[$key]);
            $this->set($key,'', -1);
        }
    }
}