<?php
declare(strict_types=1);

namespace App;

use App\Exception\NotFoundException;
use App\Exception\UnauthorizedException;
use App\Repository\UserRepository;
use App\Security\CookieTokenSecurity;
use App\Security\SecurityInterface;
use App\Services\Cookie;
use App\Services\Session;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Setup;
use \Swift_Mailer;
use \Swift_SmtpTransport;

class bootstrap
{
    /**
     * @param array $server
     * @throws NotFoundException
     */
    public function run(array $server)
    {
        session_start();
        $container = $this->loadContainer();
        $router = new router($server['REQUEST_URI']);
    
        if(getenv('check_security')) {
            /**
             * @var SecurityInterface $security
             */
            $security = $container->get(SecurityInterface::class);
            if(!$security->isAuthenticated()) {
                throw new UnauthorizedException('Need to authenticate!');
            }
        }
        
        $controller = $container->get("App\\Controllers\\{$router->getController()}");
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
        $config->setAutoGenerateProxyClasses(true);
        return EntityManager::create($params, $config);
    }
    
    /**
     * @return container
     */
    public function loadContainer() : container
    {
        $this->loadEnv();
        $container = new container();
        
        $container->add(EntityManagerInterface::class, function(){
            return $this->configOrm() instanceof EntityManagerInterface ? $this->configOrm() : null;
        });
        
        $container->add(Swift_SmtpTransport::class, function (){
            return new Swift_SmtpTransport( getenv('mailer_dns'),getenv('mailer_port') );
        });

        $container->add(Swift_Mailer::class, function () use($container){
            return new Swift_Mailer( $container->get(Swift_SmtpTransport::class) );
        });
        
        $container->add(SecurityInterface::class, function () use ($container){
            $security = new CookieTokenSecurity(
                $container->get(Cookie::class),
                $container->get(Session::class),
                $container->get(UserRepository::class)
            );
            return $security instanceof SecurityInterface ? $security : null;
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