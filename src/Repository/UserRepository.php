<?php
declare(strict_types=1);

namespace App\Repository;

use App\Models\Users;

class UserRepository extends AbstractRepository
{
    /**
     * @var Users
     */
    protected $entity = Users::class;
    
    /**
     * @return array
     */
    public function getSubscribedProductsPrices() : array
    {
        $result = [];

        $query =
            "select p.name, p.id as product_id, l.link, l.shop, u.email, u.id as user_id, pr.price, pr.id as price_id
                from {$this->entity} u
                inner join u.products p with p.active = true
                inner join p.prices pr with pr.notified = false
                inner join p.links l with l.active = true and l.shop = pr.shop";

        $data = $this->manager->createQuery($query)->getArrayResult();
        
        foreach ($data as $item) {
            $result[$item['user_id']]['email'] = $item['email'];
    
            $result[$item['user_id']]['products'][$item['product_id']]['name'] = $item['name'];
            
            $result[$item['user_id']]['products'][$item['product_id']]['links'][] = [
                'price'     => $item['price'],
                'link'      => $item['link'],
                'shop'      => $item['shop'],
                'price_id'  => $item['price_id']
            ];
            
        }

        return $result;
    }
}