<?php
declare(strict_types=1);

namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Parse extends Command
{
    protected static $defaultName = 'parse';
    
    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
    
    }
    
    private function getAllProducts()
    {
    
    }
}