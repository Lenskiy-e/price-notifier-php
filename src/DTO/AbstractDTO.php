<?php
declare(strict_types=1);

namespace App\DTO;

use App\Exception\DTOException;

class AbstractDTO
{
    protected function checkField(string $field, array $data)
    {
        if(!isset($data[$field])) {
            throw new DTOException("Field {$field} not found");
        }
    }
}