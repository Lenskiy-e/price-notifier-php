<?php

use App\bootstrap;
use App\Commands\Parse;
use Symfony\Component\Console\Application;

require __DIR__ . '/../vendor/autoload.php';

$application = new Application();
$bootstrap = new bootstrap();
$container = $bootstrap->loadContainer();

$application->add($container->getService(Parse::class));
$application->add($container->getService(\App\Commands\Fixtures::class));

$application->run();