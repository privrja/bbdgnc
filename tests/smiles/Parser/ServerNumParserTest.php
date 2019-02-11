<?php

namespace Bbdgnc\Test\Smiles\Parser;

use Bbdgnc\Finder\Enum\ServerEnum;
use Bbdgnc\Smiles\Parser\Accept;
use Bbdgnc\Smiles\Parser\ServerNumParser;
use PHPUnit\Framework\TestCase;

final class ServerNumParserTest extends TestCase {

    public function testWithNull() {
        $parser = new ServerNumParser();
        $this->assertEquals(ServerNumParser::reject(), $parser->parse(null));
    }

    public function testWithEmptyString() {
        $parser = new ServerNumParser();
        $this->assertEquals(ServerNumParser::reject(), $parser->parse(''));
    }

    public function testWithRightData() {
        $parser = new ServerNumParser();
        $this->assertEquals(new Accept(ServerEnum::PUBCHEM, ''), $parser->parse('CID: '));
    }

    public function testWithRightData2() {
        $parser = new ServerNumParser();
        $this->assertEquals(new Accept(ServerEnum::PUBCHEM, '15'), $parser->parse('CID: 15'));
    }

    public function testWithRightData3() {
        $parser = new ServerNumParser();
        $this->assertEquals(new Accept(ServerEnum::CHEMSPIDER, ''), $parser->parse('CSID: '));
    }

    public function testWithWrongData2() {
        $parser = new ServerNumParser();
        $this->assertEquals(ServerNumParser::reject(), $parser->parse('CSID:'));
    }

    public function testWithWrongData3() {
        $parser = new ServerNumParser();
        $this->assertEquals(ServerNumParser::reject(), $parser->parse('5'));
    }

    public function testWithWrongData4() {
        $parser = new ServerNumParser();
        $this->assertEquals(ServerNumParser::reject(), $parser->parse('CID'));
    }

}
