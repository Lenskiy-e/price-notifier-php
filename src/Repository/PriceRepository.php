<?php
declare(strict_types=1);

namespace App\Repository;

use App\Models\Prices;

class PriceRepository extends AbstractRepository
{
    protected $entity = Prices::class;
    
    /**
     * @param array $ids
     * @return int|mixed|string
     */
    public function setNotify(array $ids)
    {
        $this->manager
            ->createQuery("update {$this->entity} pr set pr.notified = true where pr.id in (:ids)")
            ->setParameter('ids', $ids)
            ->getResult();
    }
}