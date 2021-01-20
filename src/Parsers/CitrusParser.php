<?php
declare(strict_types=1);

namespace App\Parsers;


use DOMDocument;

class CitrusParser implements ParserInterface
{
    
    public function parse(DOMDocument $document): float
    {
        $scripts = $document->getElementsByTagName('script');
        
        foreach ($scripts as $script) {
            if( trim($script->getAttribute('name')) == 'product_structured_data' ) {
                $data = json_decode($script->textContent,true);
                return (float)$data['offers']['price'];
            }
        }
    }
}