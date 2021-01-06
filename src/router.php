<?php
declare(strict_types=1);

namespace App;

class router
{
    /**
     * @var array
     */
    private $path;
    
    /**
     * router constructor.
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = explode('/',trim($path, '/'));
    }
    
    /**
     * @return string
     */
    public function getController(): string
    {
        $controller = ucfirst( strtolower( $this->path[0] ) );
        return "{$controller}Controller";
    }
    
    /**
     * @return string
     */
    public function getAction(): string
    {
        return 'action' . ucfirst(strtolower($this->path[1] ?? 'index'));
    }
    
    /**
     * @return array
     */
    public function getParameters() : array
    {
        $result = array_splice($this->path, 2);
        
        foreach ($result as &$item) {
            if(is_numeric($item)) {
                $item = (int)$item;
            }
        }
        return $result;
    }
}