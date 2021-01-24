<?php
declare(strict_types=1);

namespace App\Commands;

use App\Models\Prices;
use App\Models\Users;
use App\Repository\PriceRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use App\Services\MailerService;
use App\Services\ProductService;
use Doctrine\ORM\EntityManagerInterface;
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
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var EntityManagerInterface
     */
    private $manager;
    /**
     * @var ProductRepository
     */
    private $productRepository;
    /**
     * @var PriceRepository
     */
    private $priceRepository;
    /**
     * @var MailerService
     */
    private $mailer;
    
    public function __construct(
        ProductService $productService,
        ParseService $parseService,
        UserRepository $userRepository,
        EntityManagerInterface $manager,
        ProductRepository $productRepository,
        PriceRepository $priceRepository,
        MailerService $mailer
    )
    {
        $this->productService = $productService;
        $this->parseService = $parseService;
        $this->userRepository = $userRepository;
        $this->manager = $manager;
        $this->productRepository = $productRepository;
        $this->priceRepository = $priceRepository;
        $this->mailer = $mailer;
    
        parent::__construct();
        
    }
    
    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $output->writeln('Parse process started');
        
        foreach ($this->getUsers() as $user) {
//            var_dump($this->parseService->parse( $this->getAllProducts($user) ));
//            exit();
            
            $this->createPrice( $this->parseService->parse( $this->getAllProducts($user) ) );
        }
        $this->getPricesForNotification();
        return 0;
    }
    
    private function getAllProducts(int $user_id) : array
    {
        return $this->productService->getAll($user_id,true);
    }
    
    private function getUsers() : array
    {
        $users = [];

        foreach ( $this->userRepository->findBy(['active' => true]) as $user) {
            $users[] = $user->getId();
        }
        return $users;
    }
    
    private function createPrice(array $priceData)
    {
        foreach ($priceData as $link) {
            $product = $this->productRepository->findById($link['product']);
            $price = new Prices();
            
            $price->setProduct($product);
            $price->setShop($link['shop']);
            $price->setPrice($link['price']);
    
            $this->manager->persist($price);
            $this->manager->flush();
        }
    }
    
    private function getPricesForNotification() : array
    {
        $data = $this->userRepository->getSubscribedProductsPrices();
        var_dump($data);
        exit();
    }
}