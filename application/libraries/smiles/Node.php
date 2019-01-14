<?php

namespace Bbdgnc\Smiles;

class Node {

    private $atom;

    private $invariants;

    private $rank;

    private $arBounds = array();

    /**
     * Node constructor.
     * @param String $atom
     * @param array $arBounds
     */
    public function __construct($atom, $arBounds) {
        $this->atom = $atom;
        $this->arBounds = $arBounds;
    }

}
