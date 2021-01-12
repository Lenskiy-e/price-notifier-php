<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Services\Session;

class HomeController
{
    /**
     * @var Session
     */
    private $session;
    
    public function __construct(Session $session)
    {
        $this->session = $session;
    }
    
    public function actionIndex()
    {
        $user = $this->session->getUser();
        if($user) {
            var_dump($user->getProducts()->toArray());
        }else{
            echo "Please, log in";
        }
    }
    
    public function actionLogin()
    {
        echo "Login form";
    }
}