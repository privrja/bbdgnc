<?php

namespace Bbdgnc\Smiles;

use Bbdgnc\Exception\IllegalStateException;

class SmilesNumber extends AbstractSmileNumber {

    public function isInPair(): bool {
        return false;
    }

    public function getNumber(): int {
        throw new IllegalStateException();
    }
}
