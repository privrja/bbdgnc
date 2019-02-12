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
        $reference = new ReferenceTO();
        $reference->server = ServerEnum::PUBCHEM;
        $reference->identifier = 15;
        $this->assertEquals(new Accept($reference, ''), $parser->parse('CID: 15'));
    }

    public function testWithRightData2() {
        $parser = new ServerNumReferenceParser();
        $reference = new ReferenceTO();
        $reference->server = ServerEnum::CHEMSPIDER;
        $reference->identifier = 623546;
        $this->assertEquals(new Accept($reference, ''), $parser->parse('CSID: 623546'));
    }

    public function testWithRightData3() {
        $parser = new ServerNumReferenceParser();
        $reference = new ReferenceTO();
        $reference->server = ServerEnum::CHEMSPIDER;
        $reference->identifier = 1;
        $this->assertEquals(new Accept($reference, ''), $parser->parse('CSID: 1'));
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