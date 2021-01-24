<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Repository\UserRepository;
use App\Services\ProductService;
use App\Services\Request;
use App\Services\Session;

class HomeController extends AbstractController
{
    /**
     * @var Session
     */
    private $session;
    /**
     * @var ProductService
     */
    private $productService;
    /**
     * @var Request
     */
    private $request;
    /**
     * @var UserRepository
     */
    private $userRepository;
    
    /**
     * HomeController constructor.
     * @param Session $session
     * @param ProductService $productService
     * @param Request $request
     */
    public function __construct
    (
        Session $session,
        ProductService $productService,
        Request $request,
        UserRepository $userRepository
    )
    {
        $this->session = $session;
        $this->productService = $productService;
        $this->request = $request;

        parent::__construct($request);
        $this->userRepository = $userRepository;
    }
    
    public function actionIndex()
    {
        $users = $this->userRepository->getSubscribedProductsPrices();
        var_dump($users);
        exit();
        $user = $this->session->getUser();
        if($user) {
            $this->response( $this->productService->getUserProducts(), 200 );
        }else{
            echo "Please, log in";
        }
    }
    
    public function actionLogin()
    {
        echo "Login form";
    }
}