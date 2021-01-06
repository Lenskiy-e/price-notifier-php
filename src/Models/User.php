<?php
declare(strict_types=1);
namespace App\Models;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

/**
 * @Entity()
 * @Table(name="`user`")
 */
class User
{
    /**
     * @Id
     * @GeneratedValue
     * @Column(type="integer")
     */
    private $id;
    
    /**
     * @Column(type="string", length=20, nullable=false)
     */
    private $name;
    
    /**
     * @Column(type="string", length=50, nullable=false, unique=true)
     */
    private $email;
    
    /**
     * @Column(type="string", length=40, nullable=true, unique=true)
     */
    private $telegram;
    
    /**
     * @Column(type="string", length=50, nullable=true, unique=true)
     */
    private $login_token;
    
    /**
     * @Column(type="string", length=50, nullable=true, unique=true)
     */
    private $security_token;
    
    
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
    public function getEmail()
    {
        return $this->email;
    }
    
    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }
    
    /**
     * @return mixed
     */
    public function getTelegram()
    {
        return $this->telegram;
    }
    
    /**
     * @param mixed $telegram
     */
    public function setTelegram($telegram)
    {
        $this->telegram = $telegram;
    }
    
    /**
     * @return mixed
     */
    public function getLoginToken()
    {
        return $this->login_token;
    }
    
    /**
     * @param mixed $login_token
     */
    public function setLoginToken($login_token)
    {
        $this->login_token = $login_token;
    }
    
    /**
     * @return mixed
     */
    public function getSecurityToken()
    {
        return $this->security_token;
    }
    
    /**
     * @param mixed $security_token
     */
    public function setSecurityToken($security_token)
    {
        $this->security_token = $security_token;
    }
    
    
}