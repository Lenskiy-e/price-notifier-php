<?php

namespace App\Security;

interface SecurityInterface
{
    /**
     * @return bool
     */
    public function isAuthenticated() : bool;
}