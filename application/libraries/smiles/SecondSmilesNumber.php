<?php

namespace Bbdgnc\Smiles;

use Bbdgnc\Base\Pair;

class SecondSmilesNumber extends PairSmilesNumber {

    /** @var OpenNumbersSort */
    private $openNumbersSort;

    /**
     * SecondSmilesNumber constructor.
     * @param int $nodeNumber
     * @param int $counter
     * @param int $pairNumber
     * @param OpenNumbersSort $openNumbersSort
     */
    public function __construct(int $nodeNumber, int $counter, int $pairNumber, OpenNumbersSort $openNumbersSort) {
        parent::__construct($nodeNumber, $counter, $pairNumber);
        $this->openNumbersSort = $openNumbersSort;
    }

    public function getNumber(): int {
        foreach ($this->openNumbersSort->getNodes()[$this->pairNumber]->getNexts() as $pair) {
            if ($pair->getSecond() === $this->nodeNumber) {
                return $pair->getFirst();
            }
        }
        return $this->openNumbersSort->getNodes()[$this->pairNumber]->getNumber();
    }

    public function next(int $pairNumber = -1) {
        $this->nexts[] = new Pair($this->getNumber(), $pairNumber);
        $this->pairNumber = $pairNumber;
        $this->increment();
        $this->length++;
    }

}
