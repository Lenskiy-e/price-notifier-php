<?php
declare(strict_types=1);

namespace App\Services;

use App\DTO\AddLinkDTO;
use App\DTO\CreateProductDTO;
use App\DTO\EditLinkDTO;
use App\Exception\NotFoundException;
use App\Models\Links;
use App\Models\Product;
use App\Repository\LinkRepository;
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
    /**
     * @var LinkRepository
     */
    private $linkRepository;
    
    /**
     * ProductService constructor.
     * @param EntityManagerInterface $entityManager
     * @param ProductRepository $productRepository
     * @param LinkRepository $linkRepository
     * @param Session $session
     */
    public function __construct
    (
        EntityManagerInterface $entityManager,
        ProductRepository $productRepository,
        LinkRepository $linkRepository,
        Session $session
    )
    {
        $this->entityManager = $entityManager;
        $this->productRepository = $productRepository;
        $this->session = $session;
        $this->linkRepository = $linkRepository;
    }
    
    /**
     * @param CreateProductDTO $dto
     */
    public function create(CreateProductDTO $dto)
    {
        $product = new Product();
        $product->setName( $dto->getName() );
        $product->setBasePrice( $dto->getBasePrice() );
        $product->setParsePrice( $dto->getParsePrice() );
        
        $this->entityManager->persist($product);
        $this->entityManager->flush();
        
        $this->addUser($product);
    }
    
    /**
     * @param Product $product
     */
    public function addUser(Product $product)
    {
        $user = $this->session->getUser();
        
        $product->getUsers()->add($user);
        $user->getProducts()->add($product);
        
        $this->entityManager->persist($product);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
    
    /**
     * @param int $id
     * @param AddLinkDTO $dto
     * @throws NotFoundException
     */
    public function addLink(int $id, AddLinkDTO $dto)
    {
        $link = new Links();

        /** @var Product $product */
        $product = $this->productRepository->findById($id);
        
        
        if(!$product) {
            throw new NotFoundException('Product not found');
        }
        
        $link->setLink( $dto->getLink() );
        $link->setShop( $dto->getShop() );
        $link->setProduct($product);
        
        $this->entityManager->persist($link);
        $this->entityManager->flush();
    }
    
    /**
     * @param int $id
     * @param EditLinkDTO $dto
     */
    public function editLink(int $id, EditLinkDTO $dto)
    {
        /** @var Links $link */
        $link = $this->linkRepository->findById($id);
        
        $link->setShop( $dto->getShop() );
        $link->setLink( $dto->getLink() );
    
        $this->entityManager->flush();
    }
    
    /**
     * @param int $id
     */
    public function deleteLink(int $id)
    {
        /** @var Links $link */
        $link = $this->linkRepository->findById($id);
        $this->entityManager->remove($link);
        $this->entityManager->flush();
    }
    
    /**
     * @param int $id
     */
    public function getLinks(int $id) : array
    {
        /** @var Product $product */
        $product = $this->productRepository->findById($id);
        $links = $product->getLinks()->toArray();
        $result = [];
        
        foreach ($links as $link) {
            $result[$link->getId()] = [
                'link'  => $link->getLink(),
                'shop'  => $link->getShop()
            ];
        }
        
        return $result;
    }

    public function getUserProducts() : array
    {
        $result = [];
        $products = $this->session->getUser()->getProducts()->toArray();
        foreach ($products as $product) {
            $result[$product->getId()] = $this->getLinks($product->getId());
        }
        return $result;
    }

    public function getAll() : array
    {

    }
}