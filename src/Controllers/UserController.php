<?php
declare(strict_types=1);

namespace App\Controllers;

use App\DTO\CreateUserDTO;
use App\DTO\UpdateUserDTO;
use App\Exception\DTOException;
use App\Exception\NotFoundException;
use App\Models\User;
use App\Repository\UserRepository;
use App\Services\Request;
use App\Services\UserService;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class UserController extends AbstractController
{
    /**
     * @var User
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
     * @param User $model
     * @param UserRepository $userRepository
     * @param Request $request
     * @param UserService $userService
     */
    public function __construct(
        User $model,
        UserRepository $userRepository,
        Request $request,
        UserService $userService
    )
    {
        $this->model = $model;
        $this->userRepository = $userRepository;
        $this->request = $request;
        $this->userService = $userService;
        
        $this->setRequest($request);
    }
    
    public function actionIndex()
    {
        echo "Hello from index";
    }
    
    public function actionGet(int $id)
    {
        var_dump($this->userRepository->findById($id));
    }
    
    public function actionCreate()
    {
        try {
            $this->checkMethod('post');
            $dto = new CreateUserDTO( $this->request->getData() );
            $this->userService->create($dto);
            $this->response([
                'status'    => 'success',
                'message'   => 'User successfully created, check your email to receive the login token'
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
        }catch (\Exception $e) {
            $this->response([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function actionUpdate(int $id)
    {
        try {
            $this->checkMethod('patch');
            $dto = new UpdateUserDTO( $this->request->getData() );
            $this->userService->update($dto, $id);
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
        }catch (\Exception $e) {
            $this->response([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function actionDelete(int $id)
    {
        try {
            $this->checkMethod('delete');
            $this->userService->delete($id);
            $this->response([
                'status'    => 'success',
                'message'   => 'Deleted!'
            ],200);
        }catch(NotFoundException $e){
            $this->returnNotFound($e->getMessage());
        }catch (\Exception $e) {
            $this->response([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}