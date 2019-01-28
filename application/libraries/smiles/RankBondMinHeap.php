<?php

namespace Bbdgnc\Smiles;

class RankBondMinHeap extends \SplMinHeap {
    /**
     * @param NextNode $value1
     * @param NextNode $value2
     */
    protected function compare($value1, $value2) {
        // TODO #, = than -, and - use normal rank
    }

}