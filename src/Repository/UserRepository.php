<?php
declare(strict_types=1);

namespace App\Repository;


use App\Models\user;

class UserRepository extends AbstractRepository
{
    protected $entity = user::class;
}