<?php
declare(strict_types=1);

namespace App\Controllers;

use App\DTO\CreateProductDTO;
use App\Exception\DTOException;
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
                'message'   => 'Product already created'
            ],400);
        }
        catch (\Exception $e) {
            $this->response([
                'status'    => 'error',
                'message'   => $e->getMessage()
            ],500);
        }
    }
}