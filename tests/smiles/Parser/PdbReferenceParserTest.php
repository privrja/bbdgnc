<?php

namespace Bbdgnc\Test\Smiles\Parser;

use Bbdgnc\Finder\Enum\ServerEnum;
use Bbdgnc\Smiles\Parser\Accept;
use Bbdgnc\Smiles\Parser\PdbReferenceParser;
use Bbdgnc\TransportObjects\ReferenceTO;
use PHPUnit\Framework\TestCase;

final class PdbReferenceParserTest extends TestCase {

    public function testWithNull() {
        $parser = new PdbReferenceParser();
        $this->assertEquals(PdbReferenceParser::reject(), $parser->parse(null));
    }

    public function testWithEmptyString() {
        $parser = new PdbReferenceParser();
        $this->assertEquals(PdbReferenceParser::reject(), $parser->parse(''));
    }

    public function testWithRightData() {
        $parser = new PdbReferenceParser();
        $this->assertEquals(new Accept(new ReferenceTO(ServerEnum::PDB, 'FOR'), ''), $parser->parse('PDB: FOR'));
    }

    public function testWithRightData2() {
        $parser = new PdbReferenceParser();
        $this->assertEquals(new Accept(new ReferenceTO(ServerEnum::PDB, 'MYR'), ' 5'), $parser->parse('PDB: MYR 5'));
    }

    public function testWithWrongData() {
        $parser = new PdbReferenceParser();
        $this->assertEquals(PdbReferenceParser::reject(), $parser->parse('PDB MYR'));
    }

    public function testWithWrongData2() {
        $parser = new PdbReferenceParser();
        $this->assertEquals(PdbReferenceParser::reject(), $parser->parse('PDB: M'));
    }

    public function testWithWrongData3() {
        $parser = new PdbReferenceParser();
        $this->assertEquals(PdbReferenceParser::reject(), $parser->parse('5'));
    }

    public function testWithWrongData4() {
        $parser = new PdbReferenceParser();
        $this->assertEquals(PdbReferenceParser::reject(), $parser->parse('PDB: FO'));
    }

}
