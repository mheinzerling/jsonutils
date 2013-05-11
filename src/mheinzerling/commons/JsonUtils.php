<?php
namespace mheinzerling\commons;

use Seld\JsonLint\JsonParser;

class JsonUtils //TODO
{
    /**
     * @throws \Seld\JsonLint\ParsingException
     */
    public static function parseToArray($json)
    {
        $array = json_decode($json, true);
        if ($array != null) return $array;
        $parser = new JsonParser();
        $parser->parse($json); //will throw an exception as the cheap php parsing failed already at this point
    }
}