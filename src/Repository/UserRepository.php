<?php
declare(strict_types=1);

namespace App\Repository;

use App\Models\Users;
use Doctrine\ORM\Query\Expr\Join;

class UserRepository extends AbstractRepository
{
    protected $entity = Users::class;
    
    public function getSubscribedProductsPrices() : array
    {
        $result = [];
//        $query = $this->qb
//            ->select('p.name, l.link, l.shop, pr.price, u.email')
//            ->from($this->entity, 'u')
//            ->innerJoin('u.products', 'p', Join::WITH, 'p.active=true')
//            ->innerJoin('p.prices', 'pr')
//            ->innerJoin('p.links', 'l')
//            ->where('u.active=true');

        $query =
            "select p.name, p.id as product_id, l.link, l.shop, u.email, u.id as user_id, pr.price from {$this->entity} u
                inner join u.products p with p.active = true
                inner join p.prices pr with pr.notified = false
                inner join p.links l with l.active = true and l.shop = pr.shop";

        $data = $this->manager->createQuery($query)->getArrayResult();
        
        foreach ($data as $item) {
            $result[$item['user_id']]['email'] = $item['email'];
    
            $result[$item['user_id']]['products'][$item['product_id']]['links'][] = [
                'name'  => $item['name'],
                'price' => $item['price'],
                'link'  => $item['link'],
                'shop'  => $item['shop']
            ];
            
        }
        var_dump($result);
        exit();
    }
}