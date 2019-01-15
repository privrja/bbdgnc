<?php

namespace Bbdgnc\Smiles;


use Bbdgnc\Smiles\Parser\SmilesParser;

class Graph {

    private $arNodes = array();

    /**
     * Graph constructor.
     * @param string $strText
     */
    public function __construct($strText) {
        $this->buildGraph($strText);
    }

    public function addNode(Element $element) {
        $this->arNodes[] = new Node($element);
    }

    public function addBond(int $nodeIndex, Bond $bond) {
        $this->arNodes[$nodeIndex]->addBond($bond);
    }

    public function buildGraph($strText) {
        $smilesParser = new SmilesParser($this);
        $result = $smilesParser->parse($strText);
        if ($result->isAccepted()) {
            // OK
        }
        // WRONG
    }

}
