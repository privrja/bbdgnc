<?php

namespace Bbdgnc\Test\Smiles\Parser;

use Bbdgnc\Finder\Enum\ServerEnum;
use Bbdgnc\Smiles\Parser\Accept;
use Bbdgnc\Smiles\Parser\ReferenceParser;
use Bbdgnc\TransportObjects\ReferenceTO;
use PHPUnit\Framework\TestCase;

final class ReferenceParserTest extends TestCase {

    public function testWithNull() {
        $parser = new ReferenceParser();
        $this->assertEquals(ReferenceParser::reject(), $parser->parse(null));
    }

    public function testWithEmptyString() {
        $parser = new ReferenceParser();
        $this->assertEquals(ReferenceParser::reject(), $parser->parse(''));
    }

    public function testWithRightData() {
        $parser = new ReferenceParser();
        $this->assertEquals(new Accept(new ReferenceTO(ServerEnum::NORINE, 'NOR00863'), ''), $parser->parse(': NOR00863'));
    }

    public function testWithRightData2() {
        $parser = new ReferenceParser();
        $this->assertEquals(new Accept(new ReferenceTO(ServerEnum::NORINE, 'NOR00001'), ' 5'), $parser->parse(': NOR00001 5'));
    }

    public function testWithRightData3() {
        $parser = new ReferenceParser();
        $this->assertEquals(new Accept(new ReferenceTO(ServerEnum::PDB, 'FOR'), ''), $parser->parse('PDB: FOR'));
    }

    public function testWithRightData4() {
        $parser = new ReferenceParser();
        $this->assertEquals(new Accept(new ReferenceTO(ServerEnum::PUBCHEM, '88'), ''), $parser->parse('CID: 88'));
    }

    public function testWithRightData5() {
        $parser = new ReferenceParser();
        $this->assertEquals(new Accept(new ReferenceTO(ServerEnum::CHEMSPIDER, '454123'), ''), $parser->parse('CSID: 454123'));
    }

    public function testWithWrongData() {
        $parser = new ReferenceParser();
        $this->assertEquals(ReferenceParser::reject(), $parser->parse('PDB MYR'));
    }

    public function testWithWrongData2() {
        $parser = new ReferenceParser();
        $this->assertEquals(ReferenceParser::reject(), $parser->parse('NOR00123'));
    }

    public function testWithWrongData3() {
        $parser = new ReferenceParser();
        $this->assertEquals(ReferenceParser::reject(), $parser->parse('5'));
    }

    public function testWithWrongData4() {
        $parser = new ReferenceParser();
        $this->assertEquals(ReferenceParser::reject(), $parser->parse(':NOR88888'));
    }

    public function testWithWrongData5() {
        $parser = new ReferenceParser();
        $this->assertEquals(ReferenceParser::reject(), $parser->parse(': 8888'));
    }

    public function testWithWrongData6() {
        $parser = new ReferenceParser();
        $this->assertEquals(ReferenceParser::reject(), $parser->parse('PDB: 4564'));
    }

    public function testWithWrongData7() {
        $parser = new ReferenceParser();
        $this->assertEquals(ReferenceParser::reject(), $parser->parse('CID: MYR'));
    }

    public function testWithWrongData8() {
        $parser = new ReferenceParser();
        $this->assertEquals(ReferenceParser::reject(), $parser->parse('CSID: NOR00864'));
    }

}
