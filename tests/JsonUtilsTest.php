<?php
declare(strict_types = 1);

namespace mheinzerling\commons;

use Seld\JsonLint\ParsingException;

class JsonUtilsTest extends \PHPUnit_Framework_TestCase
{
    public function testParse()
    {
        $actual = JsonUtils::parseToArray(file_get_contents(realpath(__DIR__) . "/../resources/sample.json"));
        $expected = [
            'menu' => [
                'id' => 'file',
                'value' => 'File',
                'popup' => [
                    'menuitem' => [
                        0 => ['value' => 'New', 'onclick' => 'CreateNewDoc()', 'taborder' => 1],
                        1 => ['value' => 'Open', 'onclick' => 'OpenDoc()', 'taborder' => 2],
                        2 => ['value' => 'Close', 'onclick' => 'CloseDoc()', 'taborder' => 3],
                    ]
                ]
            ]
        ];
        static::assertEquals($expected, $actual);
    }

    public function testParseInvalid()
    {
        try {
            JsonUtils::parseToArray("invalid");
            static::fail("Exception expected");
        } catch (ParsingException $e) {
            static::assertEquals(str_replace("\r", "", "Parse error on line 1:
invalid
^
Expected one of: 'STRING', 'NUMBER', 'NULL', 'TRUE', 'FALSE', '{', '['"), $e->getMessage());
        }

    }


    public function testRequire()
    {
        $actual = JsonUtils::required(JsonUtils::parseToArray('{"key":"value"}'), "key");
        $expected = "value";
        static::assertEquals($expected, $actual);
    }

    public function testRequireFail()
    {
        try {
            JsonUtils::required(JsonUtils::parseToArray('{"foo":1,"bar":2}'), "key");
            static::fail("Exception expected");
        } catch (ParsingException $e) {
            static::assertEquals("Missing property >key<; got: >foo, bar<", $e->getMessage());
        }
    }

    public function testOptional()
    {
        $actual = JsonUtils::optional(JsonUtils::parseToArray('{"key":"value"}'), "key", "default");
        $expected = "value";
        static::assertEquals($expected, $actual);
    }

    public function testOptionalFallback()
    {
        $actual = JsonUtils::optional(JsonUtils::parseToArray('{}'), "key", "default");
        $expected = "default";
        static::assertEquals($expected, $actual);
    }

    public function testValid()
    {
        $json = JsonUtils::parseToArray('{"key":1,"anotherKey":2}');
        JsonUtils::validProperties($json, ["key", "anotherKey"]);
        //no excpetion
    }

    public function testValidFail()
    {
        $json = JsonUtils::parseToArray('{"foo":1,"bar":2}');
        try {
            JsonUtils::validProperties($json, ["key", "anotherKey"]);
            static::fail("Exception expected");
        } catch (ParsingException $e) {
            static::assertEquals("Expected  properties >key, anotherKey<; got: >foo, bar<", $e->getMessage());
        }
    }


}