<?php

namespace Bbdgnc\Smiles;


use Bbdgnc\Exception\IllegalArgumentException;

class Graph {

    private $arNodes = array();

    /**
     * Graph constructor.
     * @param string $strSmiles SMILES
     */
    public function __construct($strSmiles) {
        $this->buildGraph($strSmiles);
    }

    private function buildGraph($strSmiles) {
        if (!isset($strSmiles) || empty($strSmiles)) {
            throw new IllegalArgumentException();
        }

        $stack = array();
        $intLength = strlen($strSmiles);
        $intIndex = 0;
        while ($intIndex < $intLength) {
            $stack[] = $strSmiles[$intIndex];

            $intIndex++;
        }

    }

}
