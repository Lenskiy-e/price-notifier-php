<?php

namespace App\Commands;

use App\Models\Links;
use App\Models\Product;
use App\Models\Users;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Fixtures extends Command
{
    protected static $defaultName = 'loadFixtures';

    /**
     * @var Users
     */
    private $user;
    /**
     * @var EntityManagerInterface
     */
    private $manager;
    /**
     * @var UserRepository
     */
    private $userRepository;
    
    /**
     * Fixtures constructor.
     * @param EntityManagerInterface $manager
     * @param UserRepository $userRepository
     */
    public function __construct
    (
        EntityManagerInterface $manager,
        UserRepository $userRepository
    )
    {
        $this->manager = $manager;
        parent::__construct();
        $this->userRepository = $userRepository;
    }
    
    protected function configure()
    {
        $this->addArgument('count', InputArgument::REQUIRED);
    }
    
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $count = $input->getArgument('count');
        $this->initUser();
        for ($i = 0; $i < $count; $i++) {
            $product = $this->createProduct();
            $this->createLink($product);
        }
        return 1;
    }
    
    /**
     * @return Product
     */
    private function createProduct() : Product
    {
        $product = new Product();
        $product->setName(md5(microtime() . rand(1,1000000000)));
        $product->setParsePrice(24999);
        $product->setBasePrice(26999);
        
        $product->getUsers()->add($this->user);
        $this->user->getProducts()->add($product);
        
        $this->manager->persist($this->user);
        $this->manager->persist($product);
        $this->manager->flush();
        
        return $product;
    }
    
    /**
     * @param Product $product
     */
    private function createLink(Product $product)
    {
        foreach (Links::SHOPS as $shop) {
            $link = new Links();
            $link->setLink('link ' . md5(microtime() . rand(1,1000000000)));
            $link->setShop($shop);
            $link->setProduct($product);
            $this->manager->persist($link);
            $this->manager->flush();
        }
        
    }
    
    private function initUser() : void
    {
        if(!$this->user) {
            $user = $this->userRepository->findOneBy(['email' => 'fixtures@user.com']);
            if(!$user) {
                $user = new Users();
                $user->setName('Fixtures');
                $user->setEmail('fixtures@user.com');
                $this->manager->persist($user);
                $this->manager->flush();
            }
            $this->user = $user;
        }
    }
}