<?php
declare(strict_types=1);

namespace App\Services;

use App\Exception\NotFoundException;

class Cookie
{
    /**
     * @param string $name
     * @param $content
     * @param int|null $expire
     * @param string $path
     */
    public function set(string $name, $content, ?int $expire = null, string $path = '/')
    {
        setcookie($name, $content, $expire, $path);
    }
    
    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key) : bool
    {
        return isset($_COOKIE[$key]);
    }
    
    /**
     * @param string $key
     * @return mixed
     * @throws NotFoundException
     */
    public function get(string $key)
    {
        if($this->has($key)) {
            throw new NotFoundException('key not found!');
        }
        return $_COOKIE[$key];
    }
    
    /**
     * @param string $key
     */
    public function remove(string $key)
    {
        if($this->has($key)) {
            unset($_COOKIE[$key]);
            $this->set($key,'', -1);
        }
    }
}