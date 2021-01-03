<?php
use App\container;
use App\exceptionHandler;
use App\router;
use App\Exception\NotFoundException;

require_once __DIR__ . '/../vendor/autoload.php';

set_exception_handler(function ($exception){
    $handler = new exceptionHandler($exception);
    $handler->handle();
});

$container = new container();
$router = new router($_SERVER['REQUEST_URI']);

$container->run();

$controller = $container->getService("App\\Controllers\\{$router->getController()}");
$action = $router->getAction();

if(!$controller) {
    throw new NotFoundException('Page not found', 404);
}

if( !method_exists($controller, $action) ) {
    throw new NotFoundException('Page not found', 404);
}

$controller->$action(...$router->getParameters());