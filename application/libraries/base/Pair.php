<?php

namespace Bbdgnc\Base;

class Pair {

    /** @var $smilesNumber */
    private $smilesNumber;

    /** @var $second */
    private $second;

    /**
     * Pair constructor.
     * @param $smilesNumber
     * @param $second
     */
    public function __construct($smilesNumber, $second) {
        $this->smilesNumber = $smilesNumber;
        $this->second = $second;
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
    public function getSecond() {
        return $this->second;
    }

}
