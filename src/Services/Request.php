<?php
declare(strict_types=1);

namespace App\Services;

class Request
{
    public function isPost() : bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
    
    public function getData() : array
    {
        if ($this->isPost()) {
            if($this->getHeader('Content-Type') === 'application/json') {
                return json_decode( file_get_contents('php://input'),true );
            }
            return $_POST;
        }
        return $_GET;
    }
    
    public function headers() : array
    {
        return getallheaders();
    }
    
    public function getHeader(string $name) : ?string
    {
        $headers = $this->headers();
        return $headers[$name] ?? null;
    }
    
    public function get(string $name): ?string
    {
        $data = $this->getData();
        return $data[$name] ?? null;
    }
    
    public function getMethod() : string
    {
        return $_SERVER['REQUEST_METHOD'];
    }
}