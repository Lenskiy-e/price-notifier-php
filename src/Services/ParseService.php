<?php
declare(strict_types=1);

namespace App\Services;

use App\Parsers\ParserFactory;
use \DOMDocument;

class ParseService
{
    
    public function parse(array $products) : string
    {
        foreach ($products as $id => $product) {
            foreach ($product['links'] as $link_id => $link) {
                $html = $this->loadPage($link['link']);
                $price = $this->getPrice($link['shop'], $html);
                var_dump($price);
                exit();
            }
        }
        return '';
    }
    
    private function loadPage(string $link): DOMDocument
    {
        $opts = [
            "http" => [
                "method" => "GET",
                "header" =>
                    "Accept-language: en\r\n" .
                    "Cookie: visid_incap_2265310=BQoWXK/+QpWEsSAmatUreyU2B2AAAAAAQUIPAAAAAABkv5ZEiWkC21ANrUPuTEtz; lang=ru; nlbi_2265310=DilEcFXzRTYNJX+CfdLtaQAAAAA1ZgGW0xQXNg+lGjJ2qxjP; incap_ses_533_2265310=92JDaCIJKlSZldk/9ZhlB0FFB2AAAAAAAm7d/UvlGtlbDBLZKJR7sg==\r\n" .
                    "User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 11_1_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.141 Safari/537.36"
            ]
        ];

        $context = stream_context_create($opts);
        $html = file_get_contents($link, false, $context);
        
        $dom = new DOMDocument();
        $internalErrors = libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        libxml_use_internal_errors($internalErrors);
        return $dom;
    }
    
    private function getPrice(string $shop, DOMDocument $document) : float
    {
        $parser = ParserFactory::getParser($shop);
        return $parser->parse($document);
    }
}