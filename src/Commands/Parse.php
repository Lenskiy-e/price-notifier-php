<?php
declare(strict_types=1);

namespace App\Commands;

use App\Models\Prices;
use App\QueueHandlers\MessageHandler;
use App\Repository\PriceRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use App\Services\MailerService;
use App\Services\ProductService;
use App\Services\QueueService;
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
    /**
     * @var QueueService
     */
    private $queue;
    
    /**
     * Parse constructor.
     * @param ProductService $productService
     * @param ParseService $parseService
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $manager
     * @param ProductRepository $productRepository
     * @param PriceRepository $priceRepository
     * @param MailerService $mailer
     * @param QueueService $queue
     */
    public function __construct(
        ProductService $productService,
        ParseService $parseService,
        UserRepository $userRepository,
        EntityManagerInterface $manager,
        ProductRepository $productRepository,
        PriceRepository $priceRepository,
        MailerService $mailer,
        QueueService $queue
    )
    {
        $this->productService = $productService;
        $this->parseService = $parseService;
        $this->userRepository = $userRepository;
        $this->manager = $manager;
        $this->productRepository = $productRepository;
        $this->priceRepository = $priceRepository;
        $this->mailer = $mailer;
        $this->queue = $queue;
    
        parent::__construct();
    }
    
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $output->writeln('Parse process started');
    
        $this->createPrice();
        $this->notify();
        
        $output->writeln('Success');
        return 0;
    }
    
    private function createPrice()
    {
        
        $priceData = $this->parseService->parse( $this->productRepository->getProductsForParse() );
        
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
    
    private function notify() : void
    {
        
        $items = $this->userRepository->getSubscribedProductsPrices();
        $price_ids = [];
    
        foreach ($items as $item) {
            $message = "<h3>There are good prices for your products: </h3>";
            $temp_ids = [];
        
            foreach ($item['products'] as $product) {
                $message .= "<p>{$product['name']} : </p>";
                $message .= "<ul>";
                foreach ($product['links'] as $link) {
                    $temp_ids[] = $link['price_id'];
                    
                    $message .= "<li> <a href='{$link['link']}' target='_blank'>{$link['shop']}</a> {$link['price']}</li>";
                }
                $message .= "</ul>";
            }
            
            $this->mailer->setMailTo($item['email']);
            $this->mailer->setSubject('Price notification');
            $this->mailer->setBody($message);
            
            $this->queue->createMessage(new MessageHandler($this->mailer));
            $price_ids = array_merge($price_ids, $temp_ids);
            unset($temp_ids);
        }
        
        $this->queue->close();
        $this->priceRepository->setNotify( array_unique($price_ids) );
    }
}