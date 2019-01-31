<?php

namespace Bbdgnc\Smiles;

use Bbdgnc\Exception\IllegalStateException;
use Bbdgnc\Exception\NotFoundException;

class OpenNumbersSort {

    /** @var SmilesNumber[] $nodes */
    private $nodes = [];

    /** @var int $length */
    private $length = 0;

    /**
     * @return AbstractSmileNumber[]
     */
    public function getNodes(): array {
        return $this->nodes;
    }

    public function addOpenNode(int $nodeNumber): void {
        $this->nodes[] = new SmilesNumber($nodeNumber, $this->getLastCounter());
        $this->length++;
    }

    public function addDigit(int $first, int $second): void {
        $firstIndex = $this->findFirst($first);
        $secondIndex = $this->findSecond($second);
        if ($this->nodes[$firstIndex]->isInPair()) {
            $this->nodes[$firstIndex]->next($secondIndex, 0);
        } else {
            $this->nodes[$firstIndex] = new FirstSmilesNumber($first, $this->nodes[$firstIndex]->getCounter() + 1, $secondIndex);
        }
        for ($index = $firstIndex + 1; $index < $this->length; ++$index) {
            $this->nodes[$index]->increment();
        }

        if ($secondIndex === $this->length) {
            if ($this->nodes[$secondIndex]->isInPair()) {
                $this->nodes[$secondIndex]->next(0, $firstIndex);
            } else {
                $this->nodes[$secondIndex] = new SecondSmilesNumber($second, $this->getLastCounter(), $firstIndex, $this);
            }
        } else {
            if ($this->nodes[$secondIndex]->isInPair()) {
                $this->nodes[$secondIndex]->next($secondIndex, $firstIndex, false);
                $this->nodes[$secondIndex]->asSecond($second, $this->getLastCounter(), $firstIndex, $this);
            } else {
                $this->nodes[$secondIndex] = new SecondSmilesNumber($second, $this->getLastCounter(), $firstIndex, $this);
            }
        }
    }

    private function getLastCounter(): int {
        if ($this->length === 0) {
            return 0;
        }
        return end($this->nodes)->getCounter();
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
