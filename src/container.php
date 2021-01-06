<?php
declare(strict_types=1);

namespace App;

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
        $objects = new \DirectoryIterator($path);

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
                    $class = new \ReflectionClass( str_replace('/', '\\', $pathToClass) );
                    $serviceName = $class->getName();
    
                    $constructor = $class->getConstructor();
    
                    if($constructor) {
                        foreach ($constructor->getParameters() as $argument) {
                            $type = (string)$argument->getType();
                            if ( $this->hasService($type) ) {
                                $serviceParameters[] = $this->getService($type);
                            }
            
                            if( !$this->hasService($type) ) {
                                $serviceParameters[] = function () use($type){
                                    return $this->getService($type);
                                };
                            }
                        }
                    }
    
                    $this->addService($serviceName, function() use($serviceName,$serviceParameters){
                        foreach ($serviceParameters as &$serviceParameter) {
                            if($serviceParameter instanceof \Closure) {
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
    public function getServices(): array
    {
        return $this->services;
    }
    
    /**
     * @param string $name
     * @return mixed|null
     */
    public function getService(string $name)
    {
        if (!$this->hasService($name)) {
            return null;
        }
        
        if ($this->services[$name] instanceof \Closure) {
            $this->services[$name] = $this->services[$name]();
        }
        
        return $this->services[$name];
    }
    
    /**
     * @param $service
     * @return bool
     */
    private function hasService($service) : bool
    {
        return isset($this->services[$service]);
    }
    
    /**
     * @param string $name
     * @param \Closure $closure
     */
    public function addService(string $name, \Closure $closure)
    {
        if(!$this->hasService($name)) {
            $this->services[$name] = $closure;
        }
    }
}