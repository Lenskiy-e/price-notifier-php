<?php
declare(strict_types=1);

namespace App\Repository;


use App\Models\Product;

class ProductRepository extends AbstractRepository
{
    protected $entity = Product::class;
}