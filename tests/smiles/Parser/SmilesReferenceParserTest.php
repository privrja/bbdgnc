<?php

namespace Bbdgnc\Test\Smiles\Parser;

use Bbdgnc\Smiles\Parser\Accept;
use Bbdgnc\Smiles\Parser\SmilesReferenceParser;
use Bbdgnc\TransportObjects\ReferenceTO;
use PHPUnit\Framework\TestCase;

class SmilesReferenceParserTest extends TestCase {

    public function testWithNull() {
        $smilesReferenceParser = new SmilesReferenceParser();
        $this->assertEquals(SmilesReferenceParser::reject(), $smilesReferenceParser->parse(null));
    }

    public function testWithEmptyString() {
        $smilesReferenceParser = new SmilesReferenceParser();
        $this->assertEquals(SmilesReferenceParser::reject(), $smilesReferenceParser->parse(''));
    }

    public function testWithRightData() {
        $smilesReferenceParser = new SmilesReferenceParser();
        $reference = new ReferenceTO();
        $reference->database = 'SMILES';
        $reference->identifier = 'CCC';
        $this->assertEquals(new Accept($reference, ''), $smilesReferenceParser->parse('SMILES: CCC'));
    }

    public function testWithWrongData() {
        $smilesReferenceParser = new SmilesReferenceParser();
        $this->assertEquals(SmilesReferenceParser::reject(), $smilesReferenceParser->parse(' C'));
    }

}