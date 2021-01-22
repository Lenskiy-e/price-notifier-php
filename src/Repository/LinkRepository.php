<?php
declare(strict_types=1);

namespace App\Repository;

use App\Models\Links;

class LinkRepository extends AbstractRepository
{
    protected $entity = Links::class;
    
    public function getProductLinks(?bool $active = null) : array
    {
        $query = $this->qb
            ->select('l.id, l.shop, l.link')
            ->from($this->entity, 'l');
        
        if($active !== null) {
            $query
                ->where('active = :active')
                ->setParameter('active', $active);
        }
        
        return $query->getQuery()->getArrayResult();
    }
}