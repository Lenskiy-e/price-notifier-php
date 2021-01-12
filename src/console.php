<?php

use App\Commands\Parse;
use Symfony\Component\Console\Application;

require __DIR__ . '/../vendor/autoload.php';

$application = new Application();
$application->add(new Parse());
$application->run();