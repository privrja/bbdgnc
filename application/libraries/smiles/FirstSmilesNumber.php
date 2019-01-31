<?php

namespace Bbdgnc\Smiles;

class FirstSmilesNumber extends PairSmilesNumber {

    public function getNumber(): int {
        return $this->getCounter();
    }

}