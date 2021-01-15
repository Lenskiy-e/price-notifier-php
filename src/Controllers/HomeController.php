<?php
declare(strict_types=1);

namespace App\Controllers;

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
     * HomeController constructor.
     * @param Session $session
     * @param ProductService $productService
     * @param Request $request
     */
    public function __construct
    (
        Session $session,
        ProductService $productService,
        Request $request
    )
    {
        $this->session = $session;
        $this->productService = $productService;
        $this->request = $request;

        parent::__construct($request);
    }
    
    public function actionIndex()
    {
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