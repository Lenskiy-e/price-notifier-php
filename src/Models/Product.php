<?php

namespace App\Models;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\JoinColumn;

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
     * @Column(type="string", unique=true)
     */
    private $name;
    
    /**
     * @Column(type="boolean")
     */
    private $active;
    
    /**
     * @Column(type="float", nullable=false)
     */
    private $base_price;
    
    /**
     * @Column(type="float", nullable=false)
     */
    private $parse_price;
    
    /**
     * @var ArrayCollection
     * @ManyToMany(targetEntity="App\Models\Users", mappedBy="products")
     * @JoinTable(name="users_product",
     *     joinColumns={@JoinColumn(name="product_id", referencedColumnName="id")},
     *     inverseJoinColumns={@JoinColumn(name="users_id", referencedColumnName="id")}
     *)
     */
    private $users;
    
    /**
     * @OneToMany(targetEntity="App\Models\Prices", mappedBy="product")
     */
    private $prices;
    
    /**
     * @OneToMany(targetEntity="App\Models\Links", mappedBy="product")
     */
    private $links;
    
    public function __construct()
    {
        $this->active = true;
        $this->users = new ArrayCollection();
        $this->links = new ArrayCollection();
        $this->prices = new ArrayCollection();
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
    
    /**
     * @return ArrayCollection
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }
    
    /**
     * @param Collection $users
     */
    public function setUsers(Collection $users): void
    {
        $this->users = $users;
    }
    
    /**
     * @return ArrayCollection
     */
    public function getLinks(): Collection
    {
        return $this->links;
    }
    
    /**
     * @param ArrayCollection $links
     */
    public function setLinks(Collection $links): void
    {
        $this->links = $links;
    }
    
    /**
     * @return mixed
     */
    public function getBasePrice()
    {
        return $this->base_price;
    }
    
    /**
     * @param mixed $base_price
     */
    public function setBasePrice($base_price): void
    {
        $this->base_price = $base_price;
    }
    
    /**
     * @return mixed
     */
    public function getParsePrice()
    {
        return $this->parse_price;
    }
    
    /**
     * @param mixed $parse_price
     */
    public function setParsePrice($parse_price): void
    {
        $this->parse_price = $parse_price;
    }
    
    /**
     * @return Collection
     */
    public function getPrices(): Collection
    {
        return $this->prices;
    }
    
    /**
     * @param Collection $prices
     */
    public function setPrices(Collection $prices): void
    {
        $this->prices = $prices;
    }
    
}