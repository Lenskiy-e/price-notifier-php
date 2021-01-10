<?php
declare(strict_types=1);

namespace App\Services;

use App\DTO\CreateProductDTO;
use App\Models\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;

class ProductService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var ProductRepository
     */
    private $productRepository;
    
    public function __construct
    (
        EntityManagerInterface $entityManager,
        ProductRepository $productRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->productRepository = $productRepository;
    }
    
    public function create(CreateProductDTO $dto)
    {
        $product = new Product();
        $product->setName( $dto->getName() );
        
        $this->entityManager->persist($product);
        $this->entityManager->flush();
    }
}