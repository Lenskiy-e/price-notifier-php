<?php


namespace App\Models;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;

/**
 * Class product
 * @package App\Models
 * @Entity()
 */
class Product
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    private $id;
    
    /**
     * @Column(type="string")
     */
    private $name;
    
    /**
     * @Column(type="boolean")
     */
    private $active;
    
    public function __construct()
    {
        $this->active = true;
    }
    
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
    
    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }
    
    /**
     * @param mixed $active
     */
    public function setActive($active): void
    {
        $this->active = $active;
    }
    
    
}