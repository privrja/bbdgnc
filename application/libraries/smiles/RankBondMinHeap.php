<?php

namespace Bbdgnc\Smiles;

use Bbdgnc\Smiles\Enum\BondTypeEnum;

class RankBondMinHeap extends \SplMinHeap {
    /**
     * @param NextNode $value1
     * @param NextNode $value2
     * @return int
     */
    protected function compare($value1, $value2) {
        // TODO #, = than -, and - use normal rank
        if (BondTypeEnum::$values[$value1->getBondType()] > BondTypeEnum::$values[$value2->getBondType()]) {
            return 1;
        } else if (BondTypeEnum::$values[$value1->getBondType()] < BondTypeEnum::$values[$value2->getBondType()]) {
            return -1;
        }
        if ($value1->getRank() < $value2->getRank()) {
            return 1;
        } else if ($value1->getRank() > $value2->getRank()) {
            return -1;
        }
        return 0;
    }

}