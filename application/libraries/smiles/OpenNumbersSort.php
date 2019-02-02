<?php

namespace Bbdgnc\Smiles;

use Bbdgnc\Exception\IllegalStateException;
use Bbdgnc\Exception\NotFoundException;

class OpenNumbersSort {

    /** @var PairSmilesNumber[] $nodes */
    private $nodes = [];

    /** @var int $length */
    private $length = 0;

    /**
     * @return PairSmilesNumber[]
     */
    public function getNodes(): array {
        return $this->nodes;
    }

    public function addOpenNode(int $nodeNumber): void {
        $this->nodes[] = new PairSmilesNumber($nodeNumber, $this->getLastCounter($this->length - 1), $this->length, $this);
        $this->length++;
    }

    public function addDigit(int $first, int $second): void {
        $firstIndex = $this->findFirst($first);
        $secondIndex = $this->findSecond($second);
        $nfsStructure = new NfsStructure($this->nodes[$firstIndex]->getCounter() + 1, $firstIndex, $secondIndex);
        $this->nodes[$firstIndex]->add($nfsStructure);
        $this->nodes[$firstIndex]->increment();
        $increment = $this->nodes[$firstIndex]->getCounter();
        for ($index = $firstIndex + 1 ; $index < $this->length; ++$index) {
            $this->nodes[$index]->incrementAll($increment);
        }
    }

    private function getLastCounter($secondIndex): int {
        if ($this->length === 0) {
            return 0;
        }
        return $this->nodes[$secondIndex]->getCounter();
    }

    /**
     * @param int $nodeNumber
     * @return int
     * @throws NotFoundException
     */
    private function findNode(int $nodeNumber): int {
        for ($index = 0; $index < $this->length; ++$index) {
            if ($this->nodes[$index]->getNodeNumber() === $nodeNumber) {
                return $index;
            }
        }
        throw new NotFoundException();
    }

    private function findFirst($first) {
        try {
            return $this->findNode($first);
        } catch (NotFoundException $exception) {
            throw new IllegalStateException();
        }
    }

    private function findSecond(int $second) {
        if ($this->nodes[$this->length - 1]->getNodeNumber() === $second) {
            return $this->length - 1;
        }
        try {
            return $this->findNode($second);
        } catch (NotFoundException $exception) {
            throw new IllegalStateException();
        }
    }
}
