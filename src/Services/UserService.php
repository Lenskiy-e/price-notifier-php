<?php
declare(strict_types=1);

namespace App\Services;

use App\DTO\CreateUserDTO;
use App\DTO\UpdateUserDTO;
use App\Exception\NotFoundException;
use App\Models\Users;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use \Swift_Message;

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
    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    
    public function __construct(
        EntityManagerInterface $entityManager,
        TokenGenerator $generator,
        UserRepository $userRepository,
        \Swift_Mailer $mailer
    )
    {
        $this->entityManager = $entityManager;
        $this->generator = $generator;
        $this->userRepository = $userRepository;
        $this->mailer = $mailer;
    }
    
    public function create(CreateUserDTO $dto) : void
    {
        $user = new Users();
        
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
            throw new NotFoundException('Users not found');
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
            throw new NotFoundException('Users not found');
        }
        
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }
    
    public function login(string $email, ?string $token = null)
    {
        if(!$token) {
            $token = $this->generator->generate(50, $email);
        }
        /**
         * @var Users $user
         */
        $user = $this->userRepository->findByEmail($email);
        
        if(!$user) {
            throw new NotFoundException();
        }
        
        $user->setLoginToken($token);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        

        $message = (new Swift_Message())
            ->setSubject('Login token')
            ->setFrom( getenv('mail_from') )
            ->setTo($email)
            ->setContentType("text/html")
            ->setBody(
                "To login click <a href='http://localhost/auth/{$token}'>here</a>"
            );
        return $this->mailer->send($message);
    }
}