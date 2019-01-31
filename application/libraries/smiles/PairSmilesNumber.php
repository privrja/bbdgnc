<?php

namespace Bbdgnc\Smiles;

use Bbdgnc\Base\Pair;

class PairSmilesNumber extends SmilesNumber {

    /** @var Pair[] $nexts */
    protected $nexts = [];

    /** @var int $length */
    protected $length = 0;

    /** @var int $pairNumber */
    protected $pairNumber = 0;

    public function __construct(int $nodeNumber, int $counter, int $pairNumber) {
        parent::__construct($nodeNumber, $counter);
        $this->pairNumber = $pairNumber;
    }

    public function isInPair(): bool {
        return true;
    }

    public function next(int $pairNumber = -1) {
        $this->nexts[] = new Pair($this->getNumber(), $this->pairNumber);
        $this->pairNumber = $pairNumber;
        $this->increment();
        $this->length++;
    }

    public function increment(): void {
        parent::increment();
        for ($index = 0; $index < $this->length; ++$index) {
            $this->nexts[$index] = new Pair($this->nexts[$index]->getFirst() + 1, $this->nexts[$index]->getSecond());
        }
    }

    public function getNexts(): array {
        return $this->nexts;
    }

    public function getLength(): int {
        return $this->length;
    }

}
