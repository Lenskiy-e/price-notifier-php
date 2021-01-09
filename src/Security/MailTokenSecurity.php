<?php
declare(strict_types=1);

namespace App\Security;

use App\Services\Cookie;
use App\Services\Session;

class MailTokenSecurity implements SecurityInterface
{
    
    /**
     * @var Cookie
     */
    private $cookie;
    /**
     * @var Session
     */
    private $session;
    
    private const NON_VALIDATE_URLS = [
        '/user/auth/{token}',
        '/user/login',
        '/'
    ];
    
    public function __construct(Cookie $cookie, Session $session)
    {
        $this->cookie = $cookie;
        $this->session = $session;
    }
    
    public function isAuthenticated(): bool
    {
        return $this->validate();
    }
    
    private function validate() : bool
    {
        foreach (self::NON_VALIDATE_URLS as $url) {
            $pattern = '~^' . preg_replace('~\{[a-zA-Z]+\}~', '[a-zA-Z0-9\=]+', $url) . '$~';

            if(preg_match($pattern, $_SERVER['REQUEST_URI'])) {
                return true;
            }
        }
        
        $tokenFromCookie = $this->cookie->get('token');
        $user = $this->session->getUser();
        
        if(!$tokenFromCookie) {
            return false;
        }
        
        return password_verify($user->getSecurityToken(),$tokenFromCookie);
    }
}