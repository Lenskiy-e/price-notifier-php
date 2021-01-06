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
     * AbstractRepository constructor.
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
    
        $this->manager = $manager;
    }
    
    public function findById($id)
    {
        return $this->manager->find($this->entity, $id);
    }
}