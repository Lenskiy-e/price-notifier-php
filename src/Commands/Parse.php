<?php
declare(strict_types=1);

namespace App\Commands;

use App\Services\ProductService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Parse extends Command
{
    protected static $defaultName = 'parse';
    /**
     * @var ProductService
     */
    private $productService;

    public function __construct
    (
        string $name = null,
        ProductService $productService
    )
    {
        parent::__construct($name);
        $this->productService = $productService;
    }

    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $output->writeln('Test');
        return 0;
    }
    
    private function getAllProducts()
    {
        $products = $this->productService->getAll();
    }
}