<?php
declare(strict_types=1);

namespace App\Repository;

use App\Models\Links;

class LinkRepository extends AbstractRepository
{
    protected $entity = Links::class;
}