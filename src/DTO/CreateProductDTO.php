<?php
declare(strict_types=1);

namespace App\DTO;

class CreateProductDTO extends AbstractDTO implements DTOInterface
{
    private $name;
    
    public function __construct(array $data)
    {
        $this->checkField('name', $data);
        
        $this->name = $data['name'];
    }
    
    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }
    
    
}