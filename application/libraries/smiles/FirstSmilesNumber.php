<?php

namespace Bbdgnc\Smiles;

use Bbdgnc\Base\Pair;

class FirstSmilesNumber extends PairSmilesNumber {

    public function getNumber(): int {
        return $this->getCounter();
    }

    public function next(int $pairNumber, int $secondPairNumber, $increment = true) {
        if ($increment) {
            $this->nexts[] = new Pair($this->getNumber(), $this->pairNumber);
        } else {
            $this->nexts[] = new Pair($this->getNumber() - 1, $this->pairNumber);
        }
        $this->pairNumber = $pairNumber;
        if ($increment) {
            $this->increment();
        }
        $this->length++;
    }

}