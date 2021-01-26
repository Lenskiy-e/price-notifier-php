<?php

use App\bootstrap;
use App\Services\QueueService;

require __DIR__ . '/../vendor/autoload.php';

$bootstrap = new bootstrap();
$bootstrap->loadContainer();

$queue = new QueueService();
$queue->consumeMessage();