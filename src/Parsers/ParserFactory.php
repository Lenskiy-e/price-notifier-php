<?php


namespace App\Parsers;


use App\Exception\NotFoundException;
use App\Models\Links;
use App\Models\Product;

class ParserFactory
{
    public static function getParser(string $shop) : ParserInterface
    {
        $shop = strtolower($shop);
        
        if( !in_array($shop, Links::SHOPS) ) {
            throw new NotFoundException("Shop {$shop} not found!");
        }
        
        switch ($shop) {
            case 'foxtrot':
                return new FoxtrotParser();
            case 'comfy':
                return new ComfyParser();
            case 'eldorado':
                return new EldoradoParser();
            case 'allo':
                return new AlloParser();
            case 'citrus':
                return new CitrusParser();
        }
    }
}