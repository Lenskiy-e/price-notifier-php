<?php
declare(strict_types=1);

namespace App;

class router
{
    /**
     * @var array
     */
    private $path;
    
    public function __construct(string $path)
    {
        $this->path = explode('/',trim($path, '/'));
    }
    
    public function getController(): string
    {
        $controller = ucfirst( strtolower( $this->path[0] ) );
        return "{$controller}Controller";
    }
    
    public function getAction(): string
    {
        return 'action' . ucfirst(strtolower($this->path[1] ?? 'index'));
    }
    
    public function getParameters() : array
    {
        return array_splice($this->path, 2);
    }
}