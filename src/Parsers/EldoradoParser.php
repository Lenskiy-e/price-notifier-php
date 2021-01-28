<?php


namespace App\Parsers;


use DOMDocument;

class EldoradoParser implements ParserInterface
{
    /**
     * @param DOMDocument $document
     * @return float
     */
    public function parse(DOMDocument $document): float
    {
        $divs = $document->getElementsByTagName('div');
        
        foreach ($divs as $div) {
            if(trim($div->getAttribute('class')) == 'price-value' && $div->getAttribute('content')) {
                return (float)$div->getAttribute('content');
            }
        }
        return 0;
    }
}