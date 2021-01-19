<?php

use App\bootstrap;
use App\Commands\Parse;
use App\container;
use Symfony\Component\Console\Application;

require __DIR__ . '/../vendor/autoload.php';

$application = new Application();
$bootstrap = new bootstrap();
$container = $bootstrap->loadContainer();

$application->add($container->getService(Parse::class));

$application->run();