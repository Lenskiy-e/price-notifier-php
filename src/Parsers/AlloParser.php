<?php


namespace App\Parsers;


use DOMDocument;

class AlloParser implements ParserInterface
{
    /**
     * @param DOMDocument $document
     * @return float
     */
    public function parse(DOMDocument $document): float
    {
        $metas = $document->getElementsByTagName('meta');

        foreach ($metas as $meta) {
            if(trim($meta->getAttribute('itemprop')) == 'price' && $meta->getAttribute('content')) {
                return (float)$meta->getAttribute('content');
            }
        }
        return 0;
    }
}