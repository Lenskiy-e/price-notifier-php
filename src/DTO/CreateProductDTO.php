<?php
declare(strict_types=1);

namespace App\DTO;

use App\Exception\DTOException;

class CreateProductDTO extends AbstractDTO
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var float
     */
    private $base_price;
    /**
     * @var float
     */
    private $parse_price;
    
    public function __construct(array $data)
    {
        $this->checkField('name', $data);
        $this->checkField('base_price', $data);
        $this->checkField('parse_price', $data);
        
        if($data['base_price'] < $data['parse_price']) {
            throw new DTOException('Base price cannot be bigger than parse price');
        }
        
        $this->name = $data['name'];
        
        $this->base_price = $data['base_price'];
        
        $this->parse_price = $data['parse_price'];
    }
    
    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }
    
    /**
     * @return float
     */
    public function getBasePrice() : float
    {
        return $this->base_price;
    }
    
    /**
     * @return float
     */
    public function getParsePrice() : float
    {
        return $this->parse_price;
    }
    
    
}