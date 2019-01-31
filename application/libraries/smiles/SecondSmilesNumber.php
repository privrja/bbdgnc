<?php

namespace Bbdgnc\Smiles;

class SecondSmilesNumber extends SmilesNumber {

    /** @var int $pairNumber */
    private $pairNumber = 0;

    /** @var OpenNumbersSort */
    private $openNumbersSort;

    /**
     * SecondSmilesNumber constructor.
     * @param int $counter
     * @param int $pairNumber
     * @param OpenNumbersSort $openNumbersSort
     */
    public function __construct(int $counter, int $pairNumber, OpenNumbersSort $openNumbersSort) {
        parent::__construct($counter);
        $this->pairNumber = $pairNumber;
        $this->openNumbersSort = $openNumbersSort;
    }

    public function isInPair(): bool {
        return true;
    }

    public function getNumber(): int {
        return $this->openNumbersSort->getNodes()[$this->pairNumber]->getNumber();
    }

}
