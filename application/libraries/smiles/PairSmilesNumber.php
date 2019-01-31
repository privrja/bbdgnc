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

    public function next(int $pairNumber = -1, $secondPairNumber, $increment = true) {
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

    public function getNexts(): array {
        return $this->nexts;
    }

    public function getLength(): int {
        return $this->length;
    }
    
    public function setNexts(array $nexts) {
        $this->nexts = $nexts;
    }

    public function asSecond(int $nodeNumber, int $counter, int $pairNumber, OpenNumbersSort $openNumbersSort) {
        $second = new SecondSmilesNumber($nodeNumber, $counter, $pairNumber, $openNumbersSort);
        $second->setNexts($this->nexts);
        return $second;
    }

}
