<?php
declare(strict_types=1);

namespace App\Repository;


use App\Models\User;

class UserRepository extends AbstractRepository
{
    protected $entity = User::class;
}