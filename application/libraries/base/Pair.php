<?php

namespace Bbdgnc\Base;

class Pair {

    /** @var $first */
    private $first;

    /** @var $second */
    private $second;

    /**
     * Pair constructor.
     * @param $first
     * @param $second
     */
    public function __construct($first, $second) {
        $this->first = $first;
        $this->second = $second;
    }

    /**
     * @return mixed
     */
    public function getFirst() {
        return $this->first;
    }

    /**
     * @return mixed
     */
    public function getSecond() {
        return $this->second;
    }

}
