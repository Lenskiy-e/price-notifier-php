<?php
declare(strict_types=1);

namespace App\Parsers;


use DOMDocument;

class ComfyParser implements ParserInterface
{
    
    public function parse(DOMDocument $document): float
    {
        $spans = $document->getElementsByTagName('span');

        foreach ($spans as $span) {
            if(trim($span->getAttribute('class')) == 'price-value' && $span->getAttribute('content')) {
                return (float)$span->getAttribute('content');
            }
        }
    }
}