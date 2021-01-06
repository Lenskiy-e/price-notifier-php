<?php
declare(strict_types=1);

namespace App\Services;

use App\DTO\DTOInterface;

class DTOHelpers
{
    public function checkFields(DTOInterface $dto)
    {
        var_dump($dto);
        exit();
    }
}