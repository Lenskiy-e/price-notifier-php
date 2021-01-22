<?php


namespace App\Parsers;


use DOMDocument;

class EldoradoParser implements ParserInterface
{
    
    public function parse(DOMDocument $document): float
    {
        $divs = $document->getElementsByTagName('div');
        
        foreach ($divs as $div) {
            if(trim($div->getAttribute('class')) == 'price-value' && $div->getAttribute('content')) {
                return (float)$div->getAttribute('content');
            }
        }
    }
}