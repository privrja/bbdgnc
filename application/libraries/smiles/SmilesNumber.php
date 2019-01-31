<?php

namespace Bbdgnc\Smiles;


use Bbdgnc\Exception\IllegalStateException;

class SmilesNumber {

    /** @var int $counter */
    protected $counter = 0;

    /**
     * SmilesNumber constructor.
     * @param int $counter
     */
    public function __construct(int $counter = 0) {
        $this->counter = $counter;
    }

    public function getCounter(): int {
        return $this->counter;
    }

    public function increment(): void {
        $this->counter++;
    }

    public function isInPair(): bool {
        return false;
    }

    public function getNumber(): int {
        throw new IllegalStateException();
    }

}
