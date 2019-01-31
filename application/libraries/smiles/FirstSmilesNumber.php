<?php

namespace Bbdgnc\Smiles;

class FirstSmilesNumber extends SmilesNumber {

    public function isInPair(): bool {
        return true;
    }

    public function getNumber(): int {
        return $this->getCounter();
    }
}