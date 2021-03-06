<?php

namespace App\DTO;

use App\Exception\DTOException;
use App\Models\Links;

class AddLinkDTO extends AbstractDTO
{
    /**
     * @var string
     */
    private $link;
    
    /**
     * @var string
     */
    private $shop;
    
    public function __construct(array $data)
    {
        $this->checkField('link', $data);
        $this->checkField('shop', $data);
        
        if( !in_array($data['shop'], Links::SHOPS) ) {
            throw new DTOException('Unknown shop provided');
        }
        
        $this->setShop($data['shop']);
        $this->setLink($data['link']);
        
    }
    
    /**
     * @return string
     */
    public function getLink() : string
    {
        return $this->link;
    }
    
    /**
     * @param string $link
     */
    public function setLink(string $link): void
    {
        $this->link = $link;
    }
    
    /**
     * @return string
     */
    public function getShop(): string
    {
        return $this->shop;
    }
    
    /**
     * @param string $shop
     */
    public function setShop(string $shop): void
    {
        $this->shop = $shop;
    }
    
}