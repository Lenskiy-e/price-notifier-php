<?php
declare(strict_types=1);

namespace App\Repository;

use App\Models\Product;
use Doctrine\ORM\Query\Expr\Join;

class ProductRepository extends AbstractRepository
{
    protected $entity = Product::class;
    
    public function getAllActiveProductsWithLinks(int $user_id) : array
    {
        $products = [];
        
        $query = $this->qb->select('p.id as product_id, p.name, p.base_price, p.parse_price, l.id as link_id, l.link, l.shop')
            ->from($this->entity, 'p')
            ->innerJoin('p.users', 'u')
            ->innerJoin('p.links', 'l', Join::WITH, 'l.active=true')
            ->where('u.id = :user_id')
            ->andWhere('p.active = true');

        $query->setParameter('user_id', $user_id);
    
        foreach ($query->getQuery()->getArrayResult() as $item) {
            $id = $item['product_id'];
            
            if(!isset($products[$id])) {
                $products[$id] = [
                    'name'          => $item['name'],
                    'base_price'    => $item['base_price'],
                    'parse_price'   => $item['parse_price'],
                ];
            }
            $products[$id]['links'][$item['link_id']] = [
                'link'  => $item['link'],
                'shop'  => $item['shop']
            ];
        }
        
        return $products;

    }
}