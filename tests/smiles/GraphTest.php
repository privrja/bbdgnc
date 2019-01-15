<?php

namespace Bbdgnc\Test\Smiles;

use Bbdgnc\Exception\IllegalArgumentException;
use Bbdgnc\Smiles\Bond;
use Bbdgnc\Smiles\Graph;
use PHPUnit\Framework\TestCase;

class GraphTest extends TestCase {

    public function testGraph() {
        $graph = new Graph("CCC");
        $expectedGraph = new Graph('');
        for ($i = 0; $i < 3; $i++) {
            $expectedGraph->addNode('C');
        }
        $expectedGraph->addBond(0, new Bond(1, ''));
        $expectedGraph->addBond(1, new Bond(0, ''));
        $expectedGraph->addBond(1, new Bond(2, ''));
        $expectedGraph->addBond(2, new Bond(1, ''));
        $this->assertEquals($expectedGraph, $graph);
    }

    public function testGraph2() {
        $graph = new Graph('C=C');
        $expectedGraph = new Graph('');
        for ($i = 0; $i < 2; $i++) {
            $expectedGraph->addNode('C');
        }
        $expectedGraph->addBond(0, new Bond(1, '='));
        $expectedGraph->addBond(1, new Bond(0, '='));
        $this->assertEquals($expectedGraph, $graph);
    }

    public function testGraph3() {
        $graph = new Graph('C=C(C)C');
        $expectedGraph = new Graph('');
        for ($i = 0; $i < 4; $i++) {
            $expectedGraph->addNode('C');
        }
        $expectedGraph->addBond(0, new Bond(1, '='));
        $expectedGraph->addBond(1, new Bond(0, '='));
        $expectedGraph->addBond(1, new Bond(2, ''));
        $expectedGraph->addBond(1, new Bond(3, ''));
        $expectedGraph->addBond(2, new Bond(1, ''));
        $expectedGraph->addBond(3, new Bond(1, ''));
        $this->assertEquals($expectedGraph, $graph);
    }

    public function testGraphWrong() {
        $this->expectException(IllegalArgumentException::class);
        new Graph('C=C(C');
    }

    public function testGraphWrong2() {
        $this->expectException(IllegalArgumentException::class);
        new Graph('C=C=(C');
    }

    public function testGraphWrong3() {
        $this->expectException(IllegalArgumentException::class);
        new Graph('C=C((C)CC)C');
    }

    public function testGraphWrong4() {
        $this->expectException(IllegalArgumentException::class);
        new Graph('C=C(');
    }

    public function testGraphWrong5() {
        $this->expectException(IllegalArgumentException::class);
        new Graph('C=CC(CC(CC)');
    }

    public function testGraph4() {
        $graph = new Graph('C=C(C)C(#O)C');
    }


}