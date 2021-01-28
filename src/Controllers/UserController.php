<?php
declare(strict_types=1);

namespace App\Controllers;

use App\DTO\CreateUserDTO;
use App\DTO\UpdateUserDTO;
use App\Exception\DTOException;
use App\Exception\NotFoundException;
use App\Models\Users;
use App\Repository\UserRepository;
use App\Services\Request;
use App\Services\UserService;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;
use Exception;

class UserController extends AbstractController
{
    /**
     * @var Users
     */
    private $model;
    /**
     * @var EntityManagerInterface
     */
    private $userRepository;
    /**
     * @var Request
     */
    private $request;

    /**
     * @var UserService
     */
    private $userService;
    
    /**
     * UserController constructor.
     * @param Users $model
     * @param UserRepository $userRepository
     * @param Request $request
     * @param UserService $userService
     */
    public function __construct(
        Users $model,
        UserRepository $userRepository,
        Request $request,
        UserService $userService
    )
    {
        $this->model = $model;
        $this->userRepository = $userRepository;
        $this->request = $request;
        $this->userService = $userService;
        
        parent::__construct($request);
    }
    
    public function actionIndex()
    {
        // TODO: action index
    }
    
    public function actionGet(int $id)
    {
        // TODO: action get
    }
    
    public function actionCreate()
    {
        try {
            $this->checkMethod('post');
            
            $dto = new CreateUserDTO( $this->request->getData() );
            
            $this->userService->create($dto);
            
            $this->response([
                'status'    => 'success',
                'message'   => 'Users successfully created, check your email to receive the login token'
            ],201);
        }catch (DTOException $e) {
            $this->response([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }catch (UniqueConstraintViolationException $e) {
            $this->response([
                'status' => 'error',
                'message' => 'Duplicate field provided'
            ], 400);
        }catch (Exception $e) {
            $this->response([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function actionUpdate()
    {
        try {
            $this->checkMethod('patch');
            
            $dto = new UpdateUserDTO( $this->request->getData() );
            
            $this->userService->update($dto);
            
            $this->response([
                'status'    => 'success',
                'message'   => 'User successfully updated'
            ],200);
        }catch (DTOException $e) {
            $this->response([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }catch (UniqueConstraintViolationException $e) {
            $this->response([
                'status' => 'error',
                'message' => 'Duplicate telegram provided'
            ], 400);
        }catch(NotFoundException $e){
            $this->returnNotFound($e->getMessage());
        }catch (Exception $e) {
            $this->response([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function actionDelete()
    {
        try {
            $this->checkMethod('delete');
            $this->userService->delete();
            $this->response([
                'status'    => 'success',
                'message'   => 'Deleted!'
            ],200);
        }catch(NotFoundException $e){
            $this->returnNotFound($e->getMessage());
        }catch (Exception $e) {
            $this->response([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function actionLogin()
    {
        try {
            $this->checkMethod('post');
            
            if(!$this->request->has('email')) {
                $this->returnNotFound('Email not found');
            }
            
            $this->userService->login( $this->request->get('email') );
        }catch(NotFoundException|NoResultException $e){
            $this->returnNotFound('User not found');
        }catch (Exception $e) {
            $this->response([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function actionAuth(?string $token = null)
    {
        try {
            $this->checkMethod('get');
            
            if(!$token) {
                throw new NotFoundException();
            }
            
            if($this->userService->auth($token)) {
                $this->redirect('/');
            }
            
        }catch(NotFoundException|NoResultException $e){
            $this->returnNotFound('Token not found');
        }catch (Exception $e) {
            $this->response([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}