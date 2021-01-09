<?php
declare(strict_types=1);
namespace App\Exception;


class UnauthorizedException extends \Exception
{
    protected $code = 401;
}