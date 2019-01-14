<?php

use Bbdgnc\Enum\PeriodicTableSingleton;
use Bbdgnc\Smiles\parsers\Accept;
use Bbdgnc\Smiles\parsers\OrganicSubsetParser;
use Bbdgnc\Smiles\parsers\Reject;

class OrganicSubsetParserTest extends \PHPUnit\Framework\TestCase {

    public function testWithNull() {
        $parser = new OrganicSubsetParser();
        $result = $parser->parse(null);
        $this->assertEquals(new Reject('Not match Organic Subset'), $result);
    }

    public function testWithEmptyString() {
        $parser = new OrganicSubsetParser();
        $result = $parser->parse('');
        $this->assertEquals(new Reject('Not match Organic Subset'), $result);
    }

    public function testWithRightData() {
        $parser = new OrganicSubsetParser();
        $result = $parser->parse('Cl');
        $this->assertEquals(new Accept(PeriodicTableSingleton::getInstance()->getAtoms()['Cl'], ''), $result);
    }

    public function testWithRightData2() {
        $parser = new OrganicSubsetParser();
        $result = $parser->parse('Fe');
        $this->assertEquals(new Accept(PeriodicTableSingleton::getInstance()->getAtoms()['F'], 'e'), $result);
    }

    public function testWithWrongData() {
        $parser = new OrganicSubsetParser();
        $result = $parser->parse('Ge');
        $this->assertEquals(OrganicSubsetParser::reject(), $result);
    }

    public function testWithWrongData2() {
        $parser = new OrganicSubsetParser();
        $result = $parser->parse('[Ge]');
        $this->assertEquals(OrganicSubsetParser::reject(), $result);
    }
}