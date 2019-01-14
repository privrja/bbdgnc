<?php

namespace Bbdgnc\Test\Smiles;

use PHPUnit\Framework\TestCase;

class GraphTest extends TestCase {

    public function testGraph() {
        $graph = new \Bbdgnc\Smiles\Graph("CCC");
    }

}