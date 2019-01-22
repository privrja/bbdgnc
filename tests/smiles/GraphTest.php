<?php

namespace Bbdgnc\Test\Smiles;

use Bbdgnc\Exception\IllegalArgumentException;
use Bbdgnc\Smiles\Bond;
use Bbdgnc\Smiles\Graph;
use PHPUnit\Framework\TestCase;

final class GraphTest extends TestCase {

    public function testWithNull() {
        $this->expectException(\TypeError::class);
        new Graph(null);
    }

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
        $this->assertEquals($graph, $expectedGraph);
    }

    public function testGraph2() {
        $graph = new Graph('C=C');
        $expectedGraph = new Graph('');
        for ($i = 0; $i < 2; $i++) {
            $expectedGraph->addNode('C');
        }
        $expectedGraph->addBond(0, new Bond(1, '='));
        $expectedGraph->addBond(1, new Bond(0, '='));
        $this->assertEquals($graph, $expectedGraph);
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
        $this->assertEquals($graph, $expectedGraph);
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

    public function testGraphWrong6() {
        $this->expectException(IllegalArgumentException::class);
        new Graph('CC(Fe)C');
    }

    public function testGraphWrong7() {
        $this->expectException(IllegalArgumentException::class);
        new Graph('CC(C))');
    }

    public function testGraphWrong8() {
        $this->expectException(IllegalArgumentException::class);
        new Graph('C%1%CC(=O)CC%1%');
    }

    public function testGraphWrong9() {
        $this->expectException(IllegalArgumentException::class);
        new Graph('C1CCC2CC1');
    }

    public function testGraph4() {
        $graph = new Graph('C=C(C)C(#O)C');
        $expectedGraph = new Graph('');
        for ($i = 0; $i < 4; $i++) {
            $expectedGraph->addNode('C');
        }
        $expectedGraph->addNode('O');
        $expectedGraph->addNode('C');
        $expectedGraph->addBond(0, new Bond(1, '='));
        $expectedGraph->addBond(1, new Bond(0, '='));
        $expectedGraph->addBond(1, new Bond(2, ''));
        $expectedGraph->addBond(1, new Bond(3, ''));
        $expectedGraph->addBond(2, new Bond(1, ''));
        $expectedGraph->addBond(3, new Bond(1, ''));
        $expectedGraph->addBond(3, new Bond(4, '#'));
        $expectedGraph->addBond(3, new Bond(5, ''));
        $expectedGraph->addBond(4, new Bond(3, '#'));
        $expectedGraph->addBond(5, new Bond(3, ''));
        $this->assertEquals($graph, $expectedGraph);
    }

    public function testGraph5() {
        $graph = new Graph('CC(CC(=O)C(C(Br)CC)C)C');
        $expectedGraph = new Graph('');
        for ($i = 0; $i < 4; $i++) {
            $expectedGraph->addNode('C');
        }
        $expectedGraph->addNode('O');
        $expectedGraph->addNode('C');
        $expectedGraph->addNode('C');
        $expectedGraph->addNode('Br');
        for ($i = 0; $i < 4; $i++) {
            $expectedGraph->addNode('C');
        }
        $expectedGraph->addBond(0, new Bond(1, ''));
        $expectedGraph->addBond(1, new Bond(0, ''));
        $expectedGraph->addBond(1, new Bond(2, ''));
        $expectedGraph->addBond(1, new Bond(11, ''));
        $expectedGraph->addBond(2, new Bond(1, ''));
        $expectedGraph->addBond(2, new Bond(3, ''));
        $expectedGraph->addBond(3, new Bond(2, ''));
        $expectedGraph->addBond(3, new Bond(4, '='));
        $expectedGraph->addBond(3, new Bond(5, ''));
        $expectedGraph->addBond(4, new Bond(3, '='));
        $expectedGraph->addBond(5, new Bond(3, ''));
        $expectedGraph->addBond(5, new Bond(6, ''));
        $expectedGraph->addBond(5, new Bond(10, ''));
        $expectedGraph->addBond(6, new Bond(5, ''));
        $expectedGraph->addBond(6, new Bond(7, ''));
        $expectedGraph->addBond(6, new Bond(8, ''));
        $expectedGraph->addBond(7, new Bond(6, ''));
        $expectedGraph->addBond(8, new Bond(6, ''));
        $expectedGraph->addBond(8, new Bond(9, ''));
        $expectedGraph->addBond(9, new Bond(8, ''));
        $expectedGraph->addBond(10, new Bond(5, ''));
        $expectedGraph->addBond(11, new Bond(1, ''));
        $this->assertEquals($graph, $expectedGraph);
    }

    public function testGraph6() {
        $graph = new Graph('C1C(C)C1');
        $expectedGraph = new Graph('');
        for ($i = 0; $i < 4; $i++) {
            $expectedGraph->addNode('C');
        }
        $expectedGraph->addBond(0, new Bond(1, ''));
        $expectedGraph->addBond(0, new Bond(3, ''));
        $expectedGraph->addBond(1, new Bond(0, ''));
        $expectedGraph->addBond(1, new Bond(2, ''));
        $expectedGraph->addBond(1, new Bond(3, ''));
        $expectedGraph->addBond(2, new Bond(1, ''));
        $expectedGraph->addBond(3, new Bond(1, ''));
        $expectedGraph->addBond(3, new Bond(0, ''));
        $this->assertEquals($graph, $expectedGraph);
    }

    public function testGraph7() {
        $graph = new Graph('C%18CC(=O)CC%18');
        $expectedGraph = new Graph('');
        for ($i = 0; $i < 3; $i++) {
            $expectedGraph->addNode('C');
        }
        $expectedGraph->addNode('O');
        $expectedGraph->addNode('C');
        $expectedGraph->addNode('C');
        $expectedGraph->addBond(0, new Bond(1, ''));
        $expectedGraph->addBond(0, new Bond(5, ''));
        $expectedGraph->addBond(1, new Bond(0, ''));
        $expectedGraph->addBond(1, new Bond(2, ''));
        $expectedGraph->addBond(2, new Bond(1, ''));
        $expectedGraph->addBond(2, new Bond(3, '='));
        $expectedGraph->addBond(2, new Bond(4, ''));
        $expectedGraph->addBond(3, new Bond(2, '='));
        $expectedGraph->addBond(4, new Bond(2, ''));
        $expectedGraph->addBond(4, new Bond(5, ''));
        $expectedGraph->addBond(5, new Bond(4, ''));
        $expectedGraph->addBond(5, new Bond(0, ''));
        $this->assertEquals($graph, $expectedGraph);
    }

    public function testGraph8() {
        $graph = new Graph('c1ccccc1');
        $expectedGraph = new Graph('');
        for ($i = 0; $i < 6; $i++) {
            $expectedGraph->addNode('c');
        }
        $expectedGraph->addBond(0, new Bond(1, ''));
        $expectedGraph->addBond(0, new Bond(5, ''));
        $expectedGraph->addBond(1, new Bond(0, ''));
        $expectedGraph->addBond(1, new Bond(2, ''));
        $expectedGraph->addBond(2, new Bond(1, ''));
        $expectedGraph->addBond(2, new Bond(3, ''));
        $expectedGraph->addBond(3, new Bond(2, ''));
        $expectedGraph->addBond(3, new Bond(4, ''));
        $expectedGraph->addBond(4, new Bond(3, ''));
        $expectedGraph->addBond(4, new Bond(5, ''));
        $expectedGraph->addBond(5, new Bond(4, ''));
        $expectedGraph->addBond(5, new Bond(0, ''));
        $this->assertEquals($graph, $expectedGraph);
    }
}