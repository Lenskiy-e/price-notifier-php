<?php

namespace App\QueueHandlers;

interface QueueHandler
{
    public function __invoke();
}