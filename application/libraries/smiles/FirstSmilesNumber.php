<?php

namespace Bbdgnc\Smiles;

use Bbdgnc\Base\SmilesNumberPair;

class FirstSmilesNumber extends PairSmilesNumber {

    public function getNumber(): int {
        return $this->getCounter();
    }

    public function next(int $pairNumber, int $secondPairNumber, $increment = true) {
        if ($increment) {
            $this->nexts[] = new SmilesNumberPair($this->getNumber(), $this->pairNumber);
            $this->increment();
        } else {
            $this->nexts[] = new SmilesNumberPair($this->getNumber() - 1, $this->pairNumber);
        }
        $this->pairNumber = $pairNumber;
        $this->length++;
    }

}