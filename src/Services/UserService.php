<?php
declare(strict_types=1);

namespace App\Services;

use App\DTO\CreateUserDTO;
use App\DTO\UpdateUserDTO;
use App\Exception\NotFoundException;
use App\Models\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var TokenGenerator
     */
    private $generator;
    /**
     * @var UserRepository
     */
    private $userRepository;
    
    public function __construct(
        EntityManagerInterface $entityManager,
        TokenGenerator $generator,
        UserRepository $userRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->generator = $generator;
        $this->userRepository = $userRepository;
    }
    
    public function create(CreateUserDTO $dto) : void
    {
        $user = new User();
        
        $user->setName($dto->getName());
        $user->setEmail($dto->getEmail());
        $user->setTelegram($dto->getTelegram());
        $user->setLoginToken($this->generator->generate(50, $dto->getEmail()));
        
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
    
    public function update(UpdateUserDTO $dto, int $id) : void
    {
        $user = $this->userRepository->findById($id);
        
        if(!$user) {
            throw new NotFoundException('User not found');
        }
        
        if($dto->getTelegram()) {
            $user->setTelegram($dto->getTelegram());
        }
    
        if($dto->getName()) {
            $user->setName($dto->getName());
        }
        $this->entityManager->flush();
    }
    
    public function delete(int $id)
    {
        $user = $this->userRepository->findById($id);
        
        if(!$user) {
            throw new NotFoundException('User not found');
        }
        
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }
}