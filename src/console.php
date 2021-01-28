<?php

use App\bootstrap;
use App\Commands\Fixtures;
use App\Commands\Parse;
use App\Exception\ContainerException;
use Symfony\Component\Console\Application;

require __DIR__ . '/../vendor/autoload.php';

$application = new Application();
$bootstrap = new bootstrap();
$container = $bootstrap->loadContainer();

try {
    $application->add($container->get(Parse::class));
    $application->add($container->get(Fixtures::class));
}catch (ContainerException $e) {
    echo $e->getMessage() . PHP_EOL;
    exit();
}

$application->run();