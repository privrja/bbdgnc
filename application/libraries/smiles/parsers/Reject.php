<?php

namespace Bbdgnc\Smiles\parsers;

use Bbdgnc\Exception\IllegalStateException;

class Reject extends ParseResult {

    /**
     * Reject constructor.
     * @param string $errorMessage
     */
    public function __construct($errorMessage) {
        $this->errorMessage = $errorMessage;
    }

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