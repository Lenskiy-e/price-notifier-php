<?php
declare(strict_types=1);

namespace App\Security;

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
     *
     */
    private const NON_VALIDATE_URLS = [
        '/user/auth/{token}',
        '/user/login'
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
        return $this->validate();
    }
    
    /**
     * @return bool
     */
    private function validate() : bool
    {
        if($this->nonAuthUrl()) {
            return true;
        }
        
        $tokenFromCookie = $this->cookie->get('token');
        $user = $this->userRepository->findById( $this->session->get('user_id') );
    
        $this->session->set('user',$user);
        
        if($_SERVER['REQUEST_URI'] == '/') {
            return true;
        }
        
        if(!$tokenFromCookie || !$user) {
            return false;
        }
        
        return password_verify($user->getSecurityToken(),$tokenFromCookie);
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
}