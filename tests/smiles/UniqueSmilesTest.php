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

    public function testMethylValine() {
        $graph = new Graph('N(C(C(=O)O)C(C)C)C');
        $this->assertEquals('CNC(C(C)C)C(O)=O', $graph->getUniqueSmiles());
    }

    public function testMethylValine2() {
        $graph = new Graph('OC(=O)C(C(C)C)NC');
        $this->assertEquals('CNC(C(C)C)C(O)=O', $graph->getUniqueSmiles());
    }

    public function testRightData2() {
        $graph = new Graph('N(C(C(=O)O)C(C(C)CC=CC)O)C');
        $this->assertEquals('CNC(C(O)C(C)CC=CC)C(O)=O', $graph->getUniqueSmiles());
    }

    public function testIsoleucine() {
        $graph = new Graph('NC(C(CC)C)C(O)=O');
        $this->assertEquals('CCC(C)C(N)C(O)=O', $graph->getUniqueSmiles());
    }

    public function testIsoleucine2() {
        $graph = new Graph('CCC(C)C(N)C(O)=O');
        $this->assertEquals('CCC(C)C(N)C(O)=O', $graph->getUniqueSmiles());
    }

    public function testIsoleucine3() {
        $graph = new Graph('NC(C(CC)C)C(=O)O');
        $this->assertEquals('CCC(C)C(N)C(O)=O', $graph->getUniqueSmiles());
    }

    public function testCyclic() {
        $graph = new Graph('OC(=O)C1C(C)CCN1');
        $this->assertEquals('CC1CCNC1C(O)=O', $graph->getUniqueSmiles());
    }

    public function testLinear() {
        $graph = new Graph('OC(=O)C(CC(C)C)O');
        $this->assertEquals('CC(C)CC(O)C(O)=O', $graph->getUniqueSmiles());
    }

    public function testBetaAlanine() {
        $graph = new Graph('OC(=O)CCN');
        $this->assertEquals('NCCC(O)=O', $graph->getUniqueSmiles());
    }

    public function testMethylAlanine() {
        $graph = new Graph('OC(=O)C(C)NC');
        $this->assertEquals('CNC(C)C(O)=O', $graph->getUniqueSmiles());
    }

    public function testAceticAcid() {
        $graph = new Graph('OC(C)=O');
        $this->assertEquals('CC(O)=O', $graph->getUniqueSmiles());
    }

    public function testDiaminopentanoicAcid() {
        $graph = new Graph('NC(CCCN)C(O)=O');
        $this->assertEquals('NCCCC(N)C(O)=O', $graph->getUniqueSmiles());
    }

    public function testProline() {
        $graph = new Graph('OC(=O)C1CCCN1');
        $this->assertEquals('OC(=O)C1CCCN1', $graph->getUniqueSmiles());
    }

    public function testPhenylAlanine() {
        $graph = new Graph('OC(=O)C(Cc1ccccc1)N');
        $this->assertEquals('NC(CC1=CC=CC=C1)C(O)=O', $graph->getUniqueSmiles());
    }

    public function testDeferoxamine() {
        $graph = new Graph('CC(=O)N(CCCCCNC(=O)CCC(=O)N(CCCCCNC(=O)CCC(=O)N(CCCCCN)O)O)O');
        $this->assertEquals('CC(=O)N(O)CCCCCNC(=O)CCC(=O)N(O)CCCCCNC(=O)CCC(=O)N(O)CCCCCN', $graph->getUniqueSmiles());
    }

    public function testAcetamide() {
        $graph = new Graph('NCCCCCN(C(C)=O)O');
        $this->assertEquals('CC(=O)N(O)CCCCCN', $graph->getUniqueSmiles());
    }

    public function testSuccinicAcid() {
        $graph = new Graph('OC(=O)CCC(=O)O');
        $this->assertEquals('OC(=O)CCC(O)=O', $graph->getUniqueSmiles());
    }

    public function testHydroxycadaverine() {
        $graph = new Graph('N(O)CCCCCN');
        $this->assertEquals('NCCCCCNO', $graph->getUniqueSmiles());
    }

}