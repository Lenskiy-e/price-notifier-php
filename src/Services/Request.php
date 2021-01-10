<?php
declare(strict_types=1);

namespace App\Services;

class Request
{
    /**
     * @return bool
     */
    public function isPost() : bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
    
    /**
     * @return array
     */
    public function getData() : array
    {
        if($this->getHeader('Content-Type') === 'application/json') {
            return json_decode( file_get_contents('php://input'),true );
        }
        return $_POST ?? $_GET;
    }
    
    /**
     * @return array
     */
    public function headers() : array
    {
        return getallheaders();
    }
    
    /**
     * @param string $name
     * @return string|null
     */
    public function getHeader(string $name) : ?string
    {
        $headers = $this->headers();
        return $headers[$name] ?? null;
    }
    
    /**
     * @param string $name
     * @return string|null
     */
    public function get(string $name): ?string
    {
        $data = $this->getData();
        return $data[$name] ?? null;
    }
    
    /**
     * @return string
     */
    public function getMethod() : string
    {
        return $_SERVER['REQUEST_METHOD'];
    }
    
    /**
     * @param string|array $key
     * @return bool
     */
    public function has($key) : bool
    {
        if(is_array($key)) {
            foreach ($key as $item) {
                if( !$this->get($item) ) {
                    return false;
                }
            }
        }
        
        if(is_string($key)) {
            if( !$this->get($key) ) {
                return false;
            }
        }
        return true;
    }
}