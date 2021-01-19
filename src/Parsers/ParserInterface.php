<?php

namespace App\Parsers;

use \DOMDocument;

interface ParserInterface
{
    public function parse(DOMDocument $document) : float;
}