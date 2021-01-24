<?php
declare(strict_types=1);

namespace App\Models;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

/**
 * Class Prices
 * @package App\Models
 * @Entity
 * @Table(name="prices")
 */
class Prices
{
    /**
     * @Id()
     * @Column(type="string", unique=true)
     */
    private $id;
    
    /**
     * @Column(type="float", nullable=false)
     */
    private $price;
    
    /**
     * @ManyToOne(targetEntity="App\Models\Product", inversedBy="prices")
     */
    private $product;
    
    /**
     * @Column(type="string", nullable=false)
     */
    private $shop;
    
    /**
     * @Column(type="integer",length=15)
     */
    private $date;
    
    /**
     * @Column(type="boolean", nullable=false)
     */
    private $notified;
    
    public function __construct()
    {
        $this->id = uniqid();
        $this->date = time();
        $this->notified = false;
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
    public function getPrice()
    {
        return $this->price;
    }
    
    /**
     * @param mixed $price
     */
    public function setPrice($price): void
    {
        $this->price = $price;
    }
    
    /**
     * @return mixed
     */
    public function getProduct()
    {
        return $this->product;
    }
    
    /**
     * @param mixed $product
     */
    public function setProduct($product): void
    {
        $this->product = $product;
    }
    
    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }
    
    /**
     * @param mixed $date
     */
    public function setDate($date): void
    {
        $this->date = $date;
    }
    
    /**
     * @return mixed
     */
    public function getShop()
    {
        return $this->shop;
    }
    
    /**
     * @param mixed $shop
     */
    public function setShop($shop): void
    {
        $this->shop = $shop;
    }
    
    /**
     * @return false
     */
    public function getNotified(): bool
    {
        return $this->notified;
    }
    
    /**
     * @param false $notified
     */
    public function setNotified(bool $notified): void
    {
        $this->notified = $notified;
    }
    
}