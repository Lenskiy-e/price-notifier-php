<?php
declare(strict_types=1);

namespace App\DTO;

class CreateUserDTO extends AbstractDTO
{
    private $name;
    private $email;
    private $telegram;
    
    public function __construct(array $data)
    {
        $this->checkField('name',$data);
        $this->checkField('email',$data);
        
        $this->name = $data['name'];
        $this->email = $data['email'];
        
        if(isset($data['telegram'])) {
            $this->telegram = $data['telegram'];
        }
    }
    
    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }
    
    /**
     * @return string
     */
    public function getEmail() : string
    {
        return $this->email;
    }
    
    /**
     * @return string|null
     */
    public function getTelegram() : ?string
    {
        return $this->telegram;
    }
    
    
}