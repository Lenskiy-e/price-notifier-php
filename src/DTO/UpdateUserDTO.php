<?php
declare(strict_types=1);

namespace App\DTO;

class UpdateUserDTO
{
    private $telegram;
    private $name;
    
    public function __construct(array $data)
    {
        if(isset($data['name'])) {
            $this->name = $data['name'];
        }
    
        if(isset($data['telegram'])) {
            $this->telegram = $data['telegram'];
        }
    }
    
    /**
     * @return mixed
     */
    public function getTelegram()
    {
        return $this->telegram;
    }
    
    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }
    
}