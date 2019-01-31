<?php

namespace Bbdgnc\Smiles;

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

    public function addOpenNode(): void {
        $this->nodes[] = new SmilesNumber($this->getLastCounter());
        $this->length++;
    }

    public function addDigit(int $first): void {
        $this->nodes[$first] = new FirstSmilesNumber($this->nodes[$first]->getCounter());
        for ($index = $first; $index < $this->length; ++$index) {
            $this->nodes[$index]->increment();
        }
        $this->nodes[] = new SecondSmilesNumber($this->getLastCounter(), $first, $this);
        $this->length++;
    }

    private function getLastCounter(): int {
        if ($this->length === 0) {
            return 0;
        }
        return end($this->nodes)->getCounter();
    }

}
