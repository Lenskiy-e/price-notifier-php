<?php
declare(strict_types=1);

namespace App\Repository;


use Doctrine\ORM\EntityManagerInterface;

class AbstractRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;
    
    /**
     * @var string
     */
    protected $entity;
    
    /**
     * @var \Doctrine\ORM\QueryBuilder
     */
    protected $qb;
    
    /**
     * AbstractRepository constructor.
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
        $this->qb = $manager->createQueryBuilder();
    }
    
    /**
     * @param $id
     * @return object|null
     */
    public function findById($id)
    {
        return $this->manager->find($this->entity, $id);
    }
    
    /**
     * @param array $params
     * @return int|mixed|string
     */
    public function findBy(array $params)
    {
        $query = $this->qb
            ->select('a')
            ->from($this->entity, 'a');
    
        foreach ($params as $key => $param) {
            $query
                ->where("a.{$key} = :{$key}")
                ->setParameter($key, $param);
        }
        
        return $query->getQuery()->getResult();
    }
    
    /**
     * @param array $params
     * @return mixed|null
     */
    public function findOneBy(array $params)
    {
        $query = $this->qb
            ->select('a')
            ->from($this->entity, 'a');
        
        foreach ($params as $key => $param) {
            $query
                ->where("a.{$key} = :{$key}")
                ->setParameter($key, $param);
        }
        
        $result = $query->getQuery()->getResult();
        
        if($result) {
            return $result[0];
        }
        
        return null;
    }
}