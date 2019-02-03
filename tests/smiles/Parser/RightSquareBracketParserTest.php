<?php

namespace Bbdgnc\Test\Smiles\Parser;

use Bbdgnc\Smiles\Parser\Accept;
use Bbdgnc\Smiles\Parser\RightSquareBracketParser;
use PHPUnit\Framework\TestCase;

final class RightSquareBracketParserTest extends TestCase {

    public function testWithNull() {
        $parser = new RightSquareBracketParser();
        $result = $parser->parse(null);
        $this->assertEquals(RightSquareBracketParser::reject(), $result);
    }

    public function testWithEmptyString() {
        $parser = new RightSquareBracketParser();
        $result = $parser->parse('');
        $this->assertEquals(RightSquareBracketParser::reject(), $result);
    }

    public function testWithRightData() {
        $parser = new RightSquareBracketParser();
        $result = $parser->parse(']');
        $this->assertEquals(new Accept(']', ''), $result);
    }

    public function testWithWrongData() {
        $parser = new RightSquareBracketParser();
        $result = $parser->parse('d');
        $this->assertEquals(RightSquareBracketParser::reject(), $result);
    }

    public function testWithWrongData2() {
        $parser = new RightSquareBracketParser();
        $result = $parser->parse('[');
        $this->assertEquals(RightSquareBracketParser::reject(), $result);
    }

}
