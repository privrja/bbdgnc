<?php

namespace Bbdgnc\Smiles;

abstract class AbstractSmileNumber {

    /** @var int $nodeNumber */
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

    public abstract function isInPair(): bool;

    public abstract function getNumber(): int;

}
