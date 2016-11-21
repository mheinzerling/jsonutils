<?php
declare(strict_types = 1);
namespace mheinzerling\commons;

use Seld\JsonLint\JsonParser;
use Seld\JsonLint\ParsingException;

class JsonUtils
{
    /**
     * @param string $json
     * @return array
     * @throws ParsingException
     */
    public static function parseToArray(string $json): array
    {
        $array = json_decode($json, true);
        if ($array != null) return $array;
        $parser = new JsonParser();
        $parser->parse($json); //will throw an exception if the cheap php parsing failed and the JSON is not empty
        return [];
    }

    /**
     * @param array $json
     * @param string $property
     * @param string|null $message
     * @return int|string|array|null
     * @throws ParsingException
     */
    public static function required(array $json, string $property, string $message = null)
    {
        if (!isset($json[$property])) {
            if ($message == null) $message = "Missing property >" . $property . "<; got: >" . implode(", ", array_keys($json)) . "<";
            throw new ParsingException($message);
        }
        return $json[$property];
    }

    /**
     * @param array $json
     * @param string $property
     * @param string|int|array|null $default
     * @return string|int|array|null
     */
    public static function optional(array $json, string $property, $default = null)
    {
        if (!isset($json[$property])) return $default;
        return $json[$property];
    }

    public static function validProperties(array $json, array $properties, $message = null): void
    {
        if (!empty(array_diff(array_keys($json), $properties))) {
            if ($message == null) $message = "Expected  properties >" . implode(", ", $properties) . "<; got: >" . implode(", ", array_keys($json)) . "<";
            throw new ParsingException($message);
        }
    }
}