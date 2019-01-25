<?php

namespace Bbdgnc\Smiles;

class Node {

    /** @var Element atom */
    private $atom;

    private $invariants;

    private $rank;

    /** @var Bond[] */
    private $arBonds = array();

    /**
     * Node constructor.
     * @param Element $atom
     * @param array $arBounds
     */
    public function __construct(Element $atom, array $arBounds = []) {
        $this->atom = $atom;
        $this->arBonds = $arBounds;
    }

    public function hydrogensCount() {
        $actualBindings = 0;
        foreach ($this->arBonds as $bond) {
            $actualBindings += $bond->getBondType();
        }
        $hydrogensCount = $this->atom->getBindings() - $actualBindings;
        return $hydrogensCount < 0 ? 0 : $hydrogensCount;
    }

    public function addBond(Bond $bond) {
        $this->arBonds[] = $bond;
    }

    /**
     * @return Element
     */
    public function getAtom(): Element {
        return $this->atom;
    }

    /**
     * @return mixed
     */
    public function getInvariants() {
        return $this->invariants;
    }

    /**
     * @return mixed
     */
    public function getRank() {
        return $this->rank;
    }

    /**
     * @return array
     */
    public function getBonds(): array {
        return $this->arBonds;
    }

}
