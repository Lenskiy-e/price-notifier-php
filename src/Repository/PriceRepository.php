<?php
declare(strict_types=1);

namespace App\Repository;

use App\Models\Prices;

class PriceRepository extends AbstractRepository
{
    /**
     * @var Prices
     */
    protected $entity = Prices::class;
    
    /**
     * @param array $ids
     */
    public function setNotify(array $ids)
    {
        $this->manager
            ->createQuery("update {$this->entity} pr set pr.notified = true where pr.id in (:ids)")
            ->setParameter('ids', $ids)
            ->getResult();
    }
}