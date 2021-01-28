<?php
declare(strict_types=1);

namespace App\Parsers;

use DOMDocument;

class FoxtrotParser implements ParserInterface
{
    /**
     * @param DOMDocument $document
     * @return float
     */
    public function parse(DOMDocument $document): float
    {
        $divs = $document->getElementsByTagName('div');
        foreach ($divs as $div) {
            if(trim($div->getAttribute('class')) == 'product-box__content') {
                return (float)$div->getAttribute('data-price');
            }
        }
        return 0;
    }
}