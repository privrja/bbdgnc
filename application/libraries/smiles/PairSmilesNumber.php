<?php

namespace Bbdgnc\Smiles;

use Bbdgnc\Base\Pair;

abstract class PairSmilesNumber extends AbstractSmileNumber {

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

    public function getNexts(): array {
        return $this->nexts;
    }

    public function setNexts(array $nexts) {
        $this->nexts = $nexts;
    }

    public function getLength(): int {
        return $this->length;
    }

    public function asSecond(int $nodeNumber, int $counter, int $pairNumber, OpenNumbersSort $openNumbersSort) {
        $second = new SecondSmilesNumber($nodeNumber, $counter, $pairNumber, $openNumbersSort);
        $second->setNexts($this->nexts);
        return $second;
    }

    public abstract function getNumber(): int;

    public abstract function next(int $pairNumber, int $secondPairNumber, $increment = true);

}
