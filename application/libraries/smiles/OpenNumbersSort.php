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
     * @return SmilesNumber[]
     */
    public function getNodes(): array {
        return $this->nodes;
    }

    public function addOpenNode(int $nodeNumber): void {
        $this->nodes[] = new SmilesNumber($nodeNumber, $this->getLastCounter());
        $this->length++;
    }

    public function addDigit(int $first, int $second): void {
        $last = array_pop($this->nodes);
        $this->length--;
        try {
            $firstIndex = $this->findNode($first);
        } catch (NotFoundException $exception) {
            throw new IllegalStateException();
        }

        if ($this->nodes[$firstIndex]->isInPair()) {
            $this->nodes[$firstIndex]->next($this->length);
        } else {
            $this->nodes[$firstIndex] = new FirstSmilesNumber($first, $this->nodes[$firstIndex]->getCounter() + 1, $this->length);
        }
        for ($index = $firstIndex + 1; $index < $this->length; ++$index) {
            $this->nodes[$index]->increment();
        }
        if ($last->isInPair()) {
            $last->next($firstIndex);
            $this->nodes[] = $last;
        } else {
            $this->nodes[] = new SecondSmilesNumber($second, $this->getLastCounter(), $firstIndex, $this);
        }
        $this->length++;
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

}
