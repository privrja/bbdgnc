<?php

use Bbdgnc\Smiles\parsers\Accept;
use Bbdgnc\Smiles\parsers\Reject;
use Bbdgnc\Smiles\parsers\StringParser;

class StringParserTest extends \PHPUnit\Framework\TestCase {

    public function testWithNull() {
        $this->expectException(\Bbdgnc\Exception\IllegalArgumentException::class);
        $parser = new StringParser();
        $parser->parseTextWithTemplate('Hello', null);
    }

    public function testWithEmptyString() {
        $parser = new StringParser();
        $parseResult = $parser->parseTextWithTemplate('', '');
        $this->assertEquals(new Accept('', ''), $parseResult);
    }

    public function testWithEmptyString2() {
        $parser = new StringParser();
        $parseResult = $parser->parseTextWithTemplate('Hello', '');
        $this->assertEquals(new Reject('Not match template'), $parseResult);
    }

    public function testWithRightData() {
        $parser = new StringParser();
        $parseResult = $parser->parseTextWithTemplate('Hello', 'Hell');
        $this->assertEquals(new Accept('Hell', 'o'), $parseResult);
    }

    public function testWithWrongData() {
        $parser = new StringParser();
        $parseResult = $parser->parseTextWithTemplate('Hello', 'ello');
        $this->assertEquals(new Reject('Not match template'), $parseResult);
    }
}