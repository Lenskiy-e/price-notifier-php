<?php
declare(strict_types=1);

namespace App\Repository;

use App\Models\Users;

class UserRepository extends AbstractRepository
{
    protected $entity = Users::class;
}