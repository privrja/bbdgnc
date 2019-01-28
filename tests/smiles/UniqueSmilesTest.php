<?php

namespace Bbdgnc\Test\Smiles;

use Bbdgnc\Smiles\Graph;
use PHPUnit\Framework\TestCase;

final class UniqueSmilesTest extends TestCase {

    public function testAceton() {
        $graph = new Graph('CC(=O)C');
        $this->assertEquals('CC(C)=O', $graph->getUniqueSmiles());
    }

    public function testRightData() {
        $graph = new Graph('OCC(CC)CCC(CN)CN');
        $this->assertEquals('CCC(CO)CCC(CN)CN', $graph->getUniqueSmiles());
    }


    public function testAminobuturicAcid() {
        $graph = new Graph('OC(C(CC)N)=O');
        $this->assertEquals('CCC(N)C(O)=O', $graph->getUniqueSmiles());
    }

    public function testSarcosine() {
        $graph = new Graph('N(CC(=O)O)C');
        $this->assertEquals('CNCC(O)=O', $graph->getUniqueSmiles());
    }

    public function testMethylLeucine() {
        $graph = new Graph('N(C(C(=O)O)CC(C)C)C');
        $this->assertEquals('CNC(CC(C)C)C(O)=O', $graph->getUniqueSmiles());
    }

    public function testValine() {
        $graph = new Graph('NC(C(=O)O)C(C)C');
        $this->assertEquals('CC(C)C(N)C(O)=O', $graph->getUniqueSmiles());
    }

    public function testAlanine() {
        $graph = new Graph('NC(C(=O)O)C');
        $this->assertEquals('CC(N)C(O)=O', $graph->getUniqueSmiles());
    }

    public function testMethylvaline() {
        $graph = new Graph('N(C(C(=O)O)C(C)C)C');
        $this->assertEquals('CNC(C(C)C)C(O)=O', $graph->getUniqueSmiles());
    }

    public function testRightData2() {
        $graph = new Graph('N(C(C(=O)O)C(C(C)CC=CC)O)C');
        $this->assertEquals('CNC(C(O)C(C)CC=CC)C(O)=O', $graph->getUniqueSmiles());
    }

    public function test() {
        $graph = new Graph('');
        $this->assertEquals('', $graph->getUniqueSmiles());
    }
}