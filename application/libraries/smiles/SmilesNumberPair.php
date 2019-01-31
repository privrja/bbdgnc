<?php

namespace Bbdgnc\Base;

class SmilesNumberPair {

    /** @var $smilesNumber */
    private $smilesNumber;

    /** @var $pairNumber */
    private $pairNumber;

    /**
     * Pair constructor.
     * @param $smilesNumber
     * @param $pairNumber
     */
    public function __construct(int $smilesNumber, int $pairNumber) {
        $this->smilesNumber = $smilesNumber;
        $this->pairNumber = $pairNumber;
    }

    /**
     * @return mixed
     */
    public function getSmilesNumber() {
        return $this->smilesNumber;
    }

    /**
     * @return mixed
     */
    public function getPairNumber() {
        return $this->pairNumber;
    }

}
