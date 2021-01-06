<?php
declare(strict_types=1);

namespace App\Services;


class TokenGenerator
{
    private const CHARS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    
    public function generate(int $length, string $additional = '') : string
    {
        $maxNumber = strlen(self::CHARS);
        $token = '';
        
        if($additional) {
            $token = base64_encode($additional . time());
            $length -= strlen($token);
        }
    
        for ($i = 0; $i < $length; $i++) {
            $token .= self::CHARS[random_int(0, $maxNumber - 1)];
        }
    
        return $token;
    }
}