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
        var_dump($this->session->getUser());
    }
}