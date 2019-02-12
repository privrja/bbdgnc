<?php

namespace Bbdgnc\Test\Smiles\Parser;

use Bbdgnc\Finder\Enum\ServerEnum;
use Bbdgnc\Smiles\Parser\Accept;
use Bbdgnc\Smiles\Parser\NorineReferenceParser;
use Bbdgnc\TransportObjects\ReferenceTO;
use PHPUnit\Framework\TestCase;

final class NorineReferenceParserTest extends TestCase {

    public function testWithNull() {
        $parser = new NorineReferenceParser();
        $this->assertEquals(NorineReferenceParser::reject(), $parser->parse(null));
    }

    public function testWithEmptyString() {
        $parser = new NorineReferenceParser();
        $this->assertEquals(NorineReferenceParser::reject(), $parser->parse(''));
    }

    public function testWithRightData() {
        $parser = new NorineReferenceParser();
        $this->assertEquals(new Accept(new ReferenceTO(ServerEnum::NORINE, 'NOR00863'), ''), $parser->parse(': NOR00863'));
    }

    public function testWithRightData2() {
        $parser = new NorineReferenceParser();
        $this->assertEquals(new Accept(new ReferenceTO(ServerEnum::NORINE, 'NOR00001'), ' 5'), $parser->parse(': NOR00001 5'));
    }

    public function testWithWrongData() {
        $parser = new NorineReferenceParser();
        $this->assertEquals(NorineReferenceParser::reject(), $parser->parse('PDB MYR'));
    }

    public function testWithWrongData2() {
        $parser = new NorineReferenceParser();
        $this->assertEquals(NorineReferenceParser::reject(), $parser->parse('NOR00123'));
    }

    public function testWithWrongData3() {
        $parser = new NorineReferenceParser();
        $this->assertEquals(NorineReferenceParser::reject(), $parser->parse('5'));
    }

    public function testWithWrongData4() {
        $parser = new NorineReferenceParser();
        $this->assertEquals(NorineReferenceParser::reject(), $parser->parse(':NOR88888'));
    }

}