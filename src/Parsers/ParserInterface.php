<?php

namespace App\Parsers;

use \DOMDocument;

interface ParserInterface
{
    /**
     * @param DOMDocument $document
     * @return float
     */
    public function parse(DOMDocument $document) : float;
}