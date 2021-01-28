<?php
declare(strict_types=1);

namespace App\Services;

use App\DTO\CreateUserDTO;
use App\DTO\UpdateUserDTO;
use App\Exception\NotFoundException;
use App\Models\Users;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Swift_Mailer;
use \Swift_Message;

class UserService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var Generator
     */
    private $generator;
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var Swift_Mailer
     */
    private $mailer;
    /**
     * @var Session
     */
    private $session;
    /**
     * @var Cookie
     */
    private $cookie;
    
    /**
     * UserService constructor.
     * @param EntityManagerInterface $entityManager
     * @param Generator $generator
     * @param UserRepository $userRepository
     * @param Swift_Mailer $mailer
     * @param Session $session
     * @param Cookie $cookie
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        Generator $generator,
        UserRepository $userRepository,
        Swift_Mailer $mailer,
        Session $session,
        Cookie $cookie
    )
    {
        $this->entityManager = $entityManager;
        $this->generator = $generator;
        $this->userRepository = $userRepository;
        $this->mailer = $mailer;
        $this->session = $session;
        $this->cookie = $cookie;
    }
    
    /**
     * @param CreateUserDTO $dto
     */
    public function create(CreateUserDTO $dto) : void
    {
        $user = new Users();
        $email = $dto->getEmail();

        $token = $this->generator->generate(50, $email);
        
        $user->setName($dto->getName());
        $user->setEmail($email);
        $user->setTelegram($dto->getTelegram());
        $user->setLoginToken($token);
        
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->sendLoginToken( $email, $token );
    }

    /**
     * @param UpdateUserDTO $dto
     */
    public function update(UpdateUserDTO $dto) : void
    {
        $user = $this->session->getUser();
        
        if($dto->getTelegram()) {
            $user->setTelegram($dto->getTelegram());
        }
    
        if($dto->getName()) {
            $user->setName($dto->getName());
        }
        $this->entityManager->flush();
    }
    
    /**
     * @throws NotFoundException
     */
    public function delete()
    {
        $user = $this->session->getUser();
        
        if(!$user) {
            throw new NotFoundException('Users not found');
        }
        
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }
    
    /**
     * @param string $email
     * @param string|null $token
     * @throws NotFoundException
     */
    public function login(string $email, ?string $token = null)
    {
        if(!$token) {
            $token = $this->generator->generate(50, $email);
        }
        /**
         * @var Users $user
         */
        $user = $this->userRepository->findOneBy([
            'email' => $email
        ]);
        
        if(!$user) {
            throw new NotFoundException();
        }
        
        $user->setLoginToken($token);
        $user->setSecurityToken(null);
        
        $this->session->remove('user');
        $this->cookie->remove('token');
        
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        
        $this->sendLoginToken($email, $token);
    }
    
    /**
     * @param string $token
     * @return bool
     * @throws NotFoundException
     */
    public function auth(string $token) : bool
    {
        /**
         * @var Users $user
         */
        $user = $this->userRepository->findOneBy([
            'login_token' => $token
        ]);
        
        if(!$user) {
            throw new NotFoundException();
        }
        
        $token = $this->generator->generate(50, $user->getEmail());
        
        $user->setSecurityToken($token);
        $user->setLoginToken(null);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        
        $this->cookie->set('token', password_hash($token,PASSWORD_BCRYPT));
        $this->session->set('user_id', $user->getId());

        return true;
    }
    
    /**
     * @param string $email
     * @param string $token
     */
    private function sendLoginToken(string $email, string $token)
    {
        $message = (new Swift_Message())
            ->setSubject('Login token')
            ->setFrom( getenv('mail_from') )
            ->setTo($email)
            ->setContentType("text/html")
            ->setBody(
                "To login click <a href='http://localhost/user/auth/{$token}'>here</a>"
            );
        $this->mailer->send($message);
    }
}