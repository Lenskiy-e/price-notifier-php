<?php
declare(strict_types=1);

namespace App\Repository;

use App\Models\Prices;

class PriceRepository extends AbstractRepository
{
    protected $entity = Prices::class;
}