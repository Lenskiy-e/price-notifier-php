<?php
namespace App\DTO;

use App\Services\DTOHelpers;

interface DTOInterface
{
    public function __construct(array $data);
}