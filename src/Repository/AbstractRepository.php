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
    
    public function findById($id)
    {
        return $this->manager->find($this->entity, $id);
    }
}