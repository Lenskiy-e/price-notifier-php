<?php
declare(strict_types=1);

namespace App\Security;

use App\Models\Users;
use App\Repository\UserRepository;
use App\Services\Cookie;
use App\Services\Session;

class CookieTokenSecurity implements SecurityInterface
{
    
    /**
     * @var Cookie
     */
    private $cookie;
    /**
     * @var Session
     */
    private $session;
    
    /**
     * @var array
     */
    private const NON_VALIDATE_URLS = [
        '/user/auth/{token}',
        '/user/login',
        '/home/login'
    ];
    /**
     * @var UserRepository
     */
    private $userRepository;
    
    /**
     * CookieTokenSecurity constructor.
     * @param Cookie $cookie
     * @param Session $session
     * @param UserRepository $userRepository
     */
    public function __construct
    (
        Cookie $cookie,
        Session $session,
        UserRepository $userRepository
    )
    {
        $this->cookie = $cookie;
        $this->session = $session;
        $this->userRepository = $userRepository;
    }
    
    /**
     * @return bool
     */
    public function isAuthenticated(): bool
    {
        if(!$this->validate()) {
            header('Location: /home/login');
        }
        return true;
    }
    
    /**
     * @return bool
     */
    private function validate() : bool
    {
        if($this->nonAuthUrl()) {
            return true;
        }
        
        if(!$this->checkCredentials()) {
            return false;
        }
        
        $tokenFromCookie = $this->cookie->get('token');
        $user = $this->userRepository->findById( $this->session->get('user_id') );
    
        $this->session->set('user',$user);
        
        $valid = $this->validateToken($user, $tokenFromCookie);
        
        if($_SERVER['REQUEST_URI'] == '/') {
            return true;
        }
        
        return $valid;
    }
    
    /**
     * @return bool
     */
    private function nonAuthUrl() : bool
    {
        foreach (self::NON_VALIDATE_URLS as $url) {
            $pattern = '~^' . preg_replace('~\{[a-zA-Z]+\}~', '[a-zA-Z0-9\=]+', $url) . '$~';
        
            if(preg_match($pattern, $_SERVER['REQUEST_URI'])) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * @return bool
     */
    private function checkCredentials() : bool
    {
        return $this->cookie->has('token') && $this->session->has('user_id');
    }
    
    /**
     * @param Users $user
     * @param string $token
     * @return bool
     */
    private function validateToken(Users $user, string $token) : bool
    {
        $valid = password_verify($user->getSecurityToken() ?? '',$token);
        
        if(!$valid) {
            $this->session->remove('user_id');
        }
        return $valid;
    }
}