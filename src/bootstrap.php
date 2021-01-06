<?php
declare(strict_types=1);

namespace App;

use App\Exception\NotFoundException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Tools\Setup;

class bootstrap
{
    /**
     * @param array $server
     * @throws NotFoundException
     */
    public function run(array $server)
    {
        $container = $this->loadContainer();
        $router = new router($server['REQUEST_URI']);
        $this->loadEnv();
        
        $controller = $container->getService("App\\Controllers\\{$router->getController()}");
        $action = $router->getAction();
    
        if(!$controller) {
            throw new NotFoundException('Page not found', 404);
        }
    
        if( !method_exists($controller, $action) ) {
            throw new NotFoundException('Page not found', 404);
        }
    
        $controller->$action(...$router->getParameters());
    }
    
    /**
     * @return EntityManager
     * @throws \Doctrine\ORM\ORMException
     */
    public function configOrm(): EntityManager
    {
        if(!getenv('db_adapter')) {
            $this->loadEnv();
        }
        $path = [__DIR__ . '/../src/Models'];
        $params = [
            'driver'   => getenv('db_adapter'),
            'user'     => getenv('db_login'),
            'password' => getenv('db_password'),
            'dbname'   => getenv('db_name'),
            'host'     => getenv('db_host')
        ];
        
        $config = Setup::createAnnotationMetadataConfiguration($path,getenv('is_dev'));
        
        return EntityManager::create($params, $config);
    }
    
    /**
     * @return container
     */
    private function loadContainer() : container
    {
        $container = new container();
        $container->addService(EntityManagerInterface::class, function(){
            return $this->configOrm() instanceof EntityManagerInterface ? $this->configOrm() : null;
        });
        $container->run();
        
        return $container;
    }
    
    /**
     *
     */
    private function loadEnv()
    {
        $path = __DIR__ . '/../.env';
        $file = fopen($path,'r');
        $params = explode(PHP_EOL, fread($file, filesize($path)));
        foreach ($params as $param) {
            putenv($param);
        }
    }
    
}