<?php

namespace Bbdgnc\Smiles;

class PairSmilesNumber {

    /** @var NfsStructure[] $nexts */
    private $nexts = [];

    /** @var int $length */
    private $length = 0;

    /** @var int $position */
    private $position = 0;

    /** @var OpenNumbersSort $openNumbersSort */
    private $openNumbersSort;

    /** @var int $nodeNumber */
    private $nodeNumber = 0;

    /** @var int $counter */
    private $counter = 0;

    /**
     * PairSmilesNumber constructor.
     * @param int $nodeNumber
     * @param int $counter
     * @param int $position
     * @param OpenNumbersSort $openNumbersSort
     */
    public function __construct(int $nodeNumber, int $counter, int $position, OpenNumbersSort $openNumbersSort) {
        $this->nodeNumber = $nodeNumber;
        $this->counter = $counter;
        $this->openNumbersSort = $openNumbersSort;
        $this->position = $position;
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
        return $this->length !== 0;
    }

    public function add(NfsStructure $nfsStructure) {
        $this->nexts[] = $nfsStructure;
        $this->length++;
        $this->openNumbersSort->getNodes()[$nfsStructure->getSecondNumber()]->addSecond($nfsStructure);
    }

    public function addSecond(NfsStructure $nfsStructure) {
        $this->nexts[] = $nfsStructure;
        $this->length++;
    }

    public function incrementAll(&$inc): void {
        self::increment();
            for ($index = 0; $index < $this->getLength(); $index++) {
            if ($inc === $this->nexts[$index]->getSmilesNumber() && $this->nexts[$index]->getFirstNumber() === $this->position) {
                $this->nexts[$index]->increment();
                $inc++;
            }
        }
    }

    /**
     * @return NfsStructure[]
     */
    public function getNexts(): array {
        return $this->nexts;
    }

    public function getLength(): int {
        return $this->length;
    }

}
