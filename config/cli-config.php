<?php

use App\bootstrap;
use Doctrine\ORM\Tools\Console\ConsoleRunner;

require_once __DIR__ . '/../src/bootstrap.php';

$bootstrap = new bootstrap();

return ConsoleRunner::createHelperSet($bootstrap->configOrm());