<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\User;

class UserController
{
    /**
     * @var User
     */
    private $model;
    
    /**
     * UserController constructor.
     * @param User $model
     */
    public function __construct(User $model)
    {
        $this->model = $model;
    }
    
    public function actionIndex()
    {
        echo "Hello from index";
    }
    
    public function actionGet(int $id)
    {
        echo $id;
    }
}