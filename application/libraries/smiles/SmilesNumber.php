<?php

namespace Bbdgnc\Smiles;

use Bbdgnc\Base\Pair;
use Bbdgnc\Exception\IllegalStateException;

class SmilesNumber {

    protected $nodeNumber = 0;

    /** @var int $counter */
    protected $counter = 0;

    /**
     * SmilesNumber constructor.
     * @param int $nodeNumber
     * @param int $counter
     */
    public function __construct(int $nodeNumber, int $counter = 0) {
        $this->nodeNumber = $nodeNumber;
        $this->counter = $counter;
    }

    public function getNodeNumber() {
        return $this->nodeNumber;
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

    public function next(int $pairNumber = -1) {
        throw new IllegalStateException();
    }

    /**
     * @return Pair[]
     */
    public function getNexts(): array {
        throw new IllegalStateException();
    }

    public function getLength(): int {
        throw new IllegalStateException();
    }

}
