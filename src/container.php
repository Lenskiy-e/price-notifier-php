<?php
declare(strict_types=1);

namespace App;

use App\Exception\ContainerException;
use \DirectoryIterator;
use \ReflectionClass;
use \Closure;

/**
 * Class container
 * @package App
 */
class container
{
    /**
     * @var array
     */
    private $services;
    /**
     * @var string
     */
    private $basePath;
    
    /**
     * container constructor.
     */
    public function __construct()
    {
        $this->basePath = __DIR__;
        $this->services = [];
    }
    
    /**
     *
     * @throws \ReflectionException
     */
    public function run()
    {
        $this->loadServices($this->basePath, 'App\\');
    }
    
    /**
     * @param string $path
     * @param string $prefix
     * @throws \ReflectionException
     */
    public function loadServices(string $path, string $prefix = '')
    {
        $objects = new DirectoryIterator($path);

        foreach ($objects as /** @var \DirectoryIterator $object */$object) {
            if($object->isDir() && !$object->isDot()) {
                $this->loadServices($object->getPathname(), $prefix);
            }

            if($object->isFile() && $object->getExtension() === 'php' &&$object->getPath() !== __DIR__) {
                $namespacePath = explode('/',"{$this->basePath}/{$object->getPath()}");
                $path = implode('\\',preg_grep('~^[A-Z].*~', $namespacePath));
                $pathToClass = "{$prefix}{$path}\\{$object->getBasename('.php')}";

                $serviceParameters = [];

                if(class_exists(str_replace('/', '\\', $pathToClass))) {
                    $class = new ReflectionClass( str_replace('/', '\\', $pathToClass) );
                    $serviceName = $class->getName();
    
                    $constructor = $class->getConstructor();
    
                    if($constructor) {
                        foreach ($constructor->getParameters() as $argument) {
                            $type = (string)$argument->getType();
                            if ( $this->has($type) ) {
                                $serviceParameters[] = $this->get($type);
                            }
            
                            if( !$this->has($type) ) {
                                $serviceParameters[] = function () use($type){
                                    return $this->get($type);
                                };
                            }
                        }
                    }
    
                    $this->add($serviceName, function() use($serviceName,$serviceParameters){
                        foreach ($serviceParameters as &$serviceParameter) {
                            if($serviceParameter instanceof Closure) {
                                $serviceParameter = $serviceParameter();
                            }
                        }
        
                        return new $serviceName(...$serviceParameters);
                    });
                }

            }
        }
    }
    
    /**
     * @return array
     */
    public function getAll(): array
    {
        return $this->services;
    }
    
    /**
     * @param string $id
     * @return mixed|null
     * @throws ContainerException
     */
    public function get(string $id)
    {
        if (!$this->has($id)) {
            throw new ContainerException("Service {$id} not found!");
        }
        
        if ($this->services[$id] instanceof Closure) {
            $this->services[$id] = $this->services[$id]();
        }
        
        return $this->services[$id];
    }
    
    /**
     * @param string $id
     * @return bool
     */
    private function has(string $id) : bool
    {
        return isset($this->services[$id]);
    }
    
    /**
     * @param string $name
     * @param \Closure $closure
     */
    public function add(string $name, Closure $closure)
    {
        if(!$this->has($name)) {
            $this->services[$name] = $closure;
        }
    }
}