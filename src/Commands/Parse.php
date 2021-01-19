<?php
declare(strict_types=1);

namespace App\Commands;

use App\Services\ProductService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Services\ParseService;

class Parse extends Command
{
    protected static $defaultName = 'parse';
    /**
     * @var ProductService
     */
    private $productService;
    /**
     * @var ParseService
     */
    private $parseService;
    
    public function __construct(ProductService $productService, ParseService $parseService)
    {
        $this->productService = $productService;
        $this->parseService = $parseService;
        parent::__construct();
    }
    
    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $output->writeln('Parse process started');
        $result = $this->parseService->parse( $this->getAllProducts() );
        return 0;
    }
    
    private function getAllProducts() : array
    {
        return $this->productService->getAll();
    }
}