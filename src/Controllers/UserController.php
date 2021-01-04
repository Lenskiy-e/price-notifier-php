<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\user;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserController
{
    /**
     * @var user
     */
    private $model;
    /**
     * @var EntityManagerInterface
     */
    private $userRepository;
    
    /**
     * UserController constructor.
     * @param user $model
     * @param UserRepository $userRepository
     */
    public function __construct(user $model, UserRepository $userRepository)
    {
        $this->model = $model;
        $this->userRepository = $userRepository;
    }
    
    public function actionIndex()
    {
        echo "Hello from index";
    }
    
    public function actionGet(int $id)
    {
        var_dump($this->userRepository->findById($id));
    }
}