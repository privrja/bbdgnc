<?php

namespace Bbdgnc\Smiles\parsers;

use Bbdgnc\Exception\IllegalStateException;

class Accept extends ParseResult {

    /**
     * @return boolean
     */
    public function isAccepted() {
        return true;
    }

    /**
     * @return mixed
     */
    public function getResult() {
        return $this->result;
    }

    /**
     * @return string
     */
    public function getErrorMessage() {
        throw new IllegalStateException();
    }

    /**
     * @return string
     */
    public function getRemainder() {
        return $this->remainder;
    }
}