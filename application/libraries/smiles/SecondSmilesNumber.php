<?php

namespace Bbdgnc\Smiles;

use Bbdgnc\Base\SmilesNumberPair;

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
            if ($pair->getPairNumber() === $this->nodeNumber) {
                return $pair->getSmilesNumber();
            }
        }
        return $this->openNumbersSort->getNodes()[$this->pairNumber]->getNumber();
    }

    public function next(int $pairNumber, int $secondPairNumber, $increment = true) {
        $this->nexts[] = new SmilesNumberPair($this->getNumber(), $secondPairNumber);
        $this->pairNumber = $secondPairNumber;
        $this->length++;
    }

}
