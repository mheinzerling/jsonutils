<?php
namespace mheinzerling\commons;

use Seld\JsonLint\JsonParser;
use Seld\JsonLint\ParsingException;

class JsonUtils //TODO
{
    /**
     * @param string $json
     * @return array
     * @throws ParsingException
     */
    public static function parseToArray(string $json):array
    {
        $array = json_decode($json, true);
        if ($array != null) return $array;
        $parser = new JsonParser();
        $parser->parse($json); //will throw an exception as the cheap php parsing failed already at this point
        throw new ParsingException("");
    }
}