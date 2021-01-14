<?php
declare(strict_types=1);

namespace App\Controllers;

use App\DTO\AddLinkDTO;
use App\DTO\CreateProductDTO;
use App\DTO\EditLinkDTO;
use App\Exception\DTOException;
use App\Exception\NotFoundException;
use App\Services\ProductService;
use App\Services\Request;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class ProductController extends AbstractController
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var ProductService
     */
    private $productService;
    
    public function __construct
    (
        Request $request,
        ProductService $productService
    )
    {
        $this->request = $request;
        $this->productService = $productService;
        
        parent::__construct($request);
    }
    
    public function actionCreate()
    {
        try {
            $this->checkMethod('post');
    
            $dto = new CreateProductDTO($this->request->getData());
            $this->productService->create($dto);
            
            $this->response([
                'status'    => 'success',
                'message'   => 'Product successfully created'
            ],201);
        }catch (DTOException $e) {
            $this->response([
                'status'    => 'error',
                'message'   => $e->getMessage()
            ],400);
        }catch (UniqueConstraintViolationException $e) {
            $this->response([
                'status'    => 'error',
                'message'   => 'Product already exists'
            ],400);
        }
        catch (\Exception $e) {
            $this->response([
                'status'    => 'error',
                'message'   => $e->getMessage()
            ],500);
        }
    }
    
    public function actionLink(int $id)
    {
        $code = 200;
        $message = '';
        
        try {
            if($this->request->isPost()) {
                $this->productService->addLink($id, new AddLinkDTO( $this->request->getData() ));
                $code = 201;
                $message = 'Link successfully created!';
            }
    
            if($this->request->isPatch()) {
                $this->productService->editLink($id, new EditLinkDTO( $this->request->getData() ));
                $message = 'Link successfully updated!';
            }
    
            if($this->request->isDelete()) {
                $this->productService->deleteLink($id);
                $message = 'Link successfully deleted!';
            }
    
            if($this->request->isGet()) {
                $message = $this->productService->getLinks($id);
            }
            
            $this->response([
                'status'    => 'success',
                'message'   => $message
            ],$code);
        }catch (DTOException $e) {
            $this->response([
                'status'    => 'error',
                'message'   => $e->getMessage()
            ],400);
        }catch (NotFoundException $e) {
            $this->response([
                'status'    => 'error',
                'message'   => $e->getMessage()
            ],404);
        }catch (UniqueConstraintViolationException $e) {
            $this->response([
                'status'    => 'error',
                'message'   => 'Link already exists'
            ],400);
        }catch (\Exception $e) {
            $this->response([
                'status'    => 'error',
                'message'   => $e->getMessage()
            ],500);
        }
    }
}