<?php

namespace Bbdgnc\Smiles\Parser;


abstract class ParseResult {
    protected $result;
    protected $errorMessage = "";
    protected $remainder = "";

    /**
     * @return boolean
     */
    public abstract function isAccepted();

    /**
     * @return mixed
     */
    public abstract function getResult();

    /**
     * @return string
     */
    public abstract function getErrorMessage();

    /**
     * @return string
     */
    public abstract function getRemainder();


}