<?php
declare(strict_types=1);

namespace App\Services;

use App\DTO\CreateProductDTO;
use App\Models\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Services\Session;

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
    /**
     * @var Session
     */
    private $session;
    
    public function __construct
    (
        EntityManagerInterface $entityManager,
        ProductRepository $productRepository,
        Session $session
    )
    {
        $this->entityManager = $entityManager;
        $this->productRepository = $productRepository;
        $this->session = $session;
    }
    
    public function create(CreateProductDTO $dto)
    {
        $product = new Product();
        $product->setName( $dto->getName() );
        
        $this->entityManager->persist($product);
        $this->entityManager->flush();
        
        $this->addUser($product);
    }
    
    public function addUser(Product $product)
    {
        $user = $this->session->getUser();
        
        $product->getUsers()->add($user);
        $user->getProducts()->add($product);
        
        $this->entityManager->persist($product);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}