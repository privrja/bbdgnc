<?php

namespace Bbdgnc\Smiles\parsers;

use Bbdgnc\Exception\IllegalStateException;

class Reject extends ParseResult {

    /**
     * @return boolean
     */
    public function isAccepted() {
        return false;
    }

    /**
     * @return mixed
     */
    public function getResult() {
        throw new IllegalStateException();
    }

    /**
     * @return string
     */
    public function getErrorMessage() {
        return $this->errorMessage;
    }

    /**
     * @return string
     */
    public function getRemainder() {
        throw new IllegalStateException();
    }
}