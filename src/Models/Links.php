<?php
declare(strict_types=1);

namespace App\Models;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * Class Links
 * @package App\Models
 * @Entity()
 */
class Links
{
    const SHOPS = [
        'comfy', 'foxtrot', 'eldorado',
        'allo', 'citrus'
    ];
    
    /**
     * @Id()
     * @Column(type="integer")
     */
    private $id;
    
    /**
     * @ManyToOne(targetEntity="App\Models\Product", inversedBy="links")
     */
    private $product;
    
    /**
     * @Column(type="string", nullable=false)
     */
    private $link;
    
    /**
     * @Column(type="simple_array")
     */
    private $shop;
    
    /**
     * @Column(type="boolean", nullable=false)
     */
    private $active;
    
    public function __construct()
    {
        $this->active = true;
        $this->product = new ArrayCollection();
    }
    
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @return Collection
     */
    public function getProduct(): Collection
    {
        return $this->product;
    }
    
    /**
     * @param Collection $product
     */
    public function setProduct(Collection $product): void
    {
        $this->product = $product;
    }
    
    /**
     * @return mixed
     */
    public function getLink()
    {
        return $this->link;
    }
    
    /**
     * @param mixed $link
     */
    public function setLink($link): void
    {
        $this->link = $link;
    }
    
    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }
    
    /**
     * @param bool $active
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
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
}