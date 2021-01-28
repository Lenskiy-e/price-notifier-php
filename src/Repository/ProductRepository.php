<?php
declare(strict_types=1);

namespace App\Repository;

use App\Models\Product;

class ProductRepository extends AbstractRepository
{
    /**
     * @var Product
     */
    protected $entity = Product::class;
    
    /**
     * @return array
     */
    public function getProductsForParse() : array
    {
        $query =
            "select distinct (p.id) as product_id, p.name, p.base_price, p.parse_price, l.id as link_id, l.link, l.shop
            from {$this->entity} p
            inner join p.users u with u.active = true
            inner join p.links l with l.active = true
            where p.active = true";
        
        return $this->productsWithLinksFormat($this->manager->createQuery($query)->getArrayResult());
    }
    
    /**
     * @param int $user_id
     * @return array
     */
    public function getAllActiveProductsWithLinks(int $user_id) : array
    {
        $query =
            "select p.id as product_id, p.name, p.base_price, p.parse_price, l.id as link_id, l.link, l.shop
            from {$this->entity} p inner join p.users u
            inner join p.links l with l.active = true
            where u.id = :user_id and p.active = true";
        
        $result = $this->manager->createQuery($query);

        $result->setParameter('user_id', $user_id);
        
        return $this->productsWithLinksFormat($result->getArrayResult());

    }
    
    /**
     * @param array $queryResult
     * @return array
     */
    private function productsWithLinksFormat(array $queryResult) : array
    {
        $products = [];
        
        foreach ($queryResult as $item) {
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