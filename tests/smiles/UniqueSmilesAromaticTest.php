<?php

namespace Bbdgnc\Test\Smiles;

use Bbdgnc\Smiles\Graph;
use PHPUnit\Framework\TestCase;

final class UniqueSmilesAromaticTest extends TestCase {

    public function testPhenylAlanine() {
        $graph = new Graph('OC(=O)C(Cc1ccccc1)N');
        $smiles = $graph->getUniqueSmiles();
        $this->assertEquals('NC(CC1=CC=CC=C1)C(O)=O', $smiles);
    }

    public function testAromatic() {
        $graph = new Graph('Cc1ccccc1');
        $smiles = $graph->getUniqueSmiles();
        $this->assertEquals('CC1=CC=CC=C1', $smiles);
    }

}