<?php

namespace Bbdgnc\Test\Smiles\Parser;

use Bbdgnc\Finder\Enum\ServerEnum;
use Bbdgnc\Smiles\Parser\Accept;
use Bbdgnc\Smiles\Parser\ServerNumReferenceParser;
use Bbdgnc\TransportObjects\ReferenceTO;
use PHPUnit\Framework\TestCase;

final class ServerNumberParserTest extends TestCase {

    public function testWithNull() {
        $parser = new ServerNumReferenceParser();
        $this->assertEquals(ServerNumReferenceParser::reject(), $parser->parse(null));
    }

    public function testWithEmptyString() {
        $parser = new ServerNumReferenceParser();
        $this->assertEquals(ServerNumReferenceParser::reject(), $parser->parse(''));
    }

    public function testWithRightData() {
        $parser = new ServerNumReferenceParser();
        $this->assertEquals(new Accept(new ReferenceTO(ServerEnum::PUBCHEM, 15), ''), $parser->parse('CID: 15'));
    }

    public function testWithRightData2() {
        $parser = new ServerNumReferenceParser();
        $this->assertEquals(new Accept(new ReferenceTO(ServerEnum::CHEMSPIDER, 623546), ''), $parser->parse('CSID: 623546'));
    }

    public function testWithRightData3() {
        $parser = new ServerNumReferenceParser();
        $this->assertEquals(new Accept(new ReferenceTO(ServerEnum::CHEMSPIDER, 1), ''), $parser->parse('CSID: 1'));
    }

    public function testWithWrongData() {
        $parser = new ServerNumReferenceParser();
        $this->assertEquals(ServerNumReferenceParser::reject(), $parser->parse('CSID: 0'));
    }

    public function testWithWrongData2() {
        $parser = new ServerNumReferenceParser();
        $this->assertEquals(ServerNumReferenceParser::reject(), $parser->parse('CSID:'));
    }

    public function testWithWrongData3() {
        $parser = new ServerNumReferenceParser();
        $this->assertEquals(ServerNumReferenceParser::reject(), $parser->parse('5'));
    }

    public function testWithWrongData4() {
        $parser = new ServerNumReferenceParser();
        $this->assertEquals(ServerNumReferenceParser::reject(), $parser->parse('CID'));
    }


}