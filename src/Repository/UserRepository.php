<?php
declare(strict_types=1);

namespace App\Repository;

use App\Models\Users;

class UserRepository extends AbstractRepository
{
    protected $entity = Users::class;
    
    public function findByEmail(string $email)
    {
        return $this->qb
            ->select('u')
            ->from(Users::class, 'u')
            ->where('u.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getSingleResult();
    }
}