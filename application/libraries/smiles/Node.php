<?php

namespace Bbdgnc\Smiles;

class Node {

    /** @var Element atom */
    private $atom;

    /** @var int $invariant */
    private $invariant;

    /** @var int $lastRank */
    private $lastRank;

    /** @var int $rank */
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

    public function actualBindings() {
        $actualBindings = 0;
        foreach ($this->arBonds as $bond) {
            $actualBindings += $bond->getBondType();
        }
        return $actualBindings;
    }

    public function hydrogensCount() {
        return $this->atom->getHydrogensCount($this->actualBindings());
    }

    public function addBond(Bond $bond) {
        $this->arBonds[] = $bond;
    }

    public function computeInvariants() {
        $this->invariant = "";
        $this->invariant .= sizeof($this->arBonds);
        $this->invariant .= $this->actualBindingsWithZero();
        $this->invariant .= $this->protonNumber();
        $this->invariant .= $this->atom->getCharge()->getSignValue();
        $this->invariant .= $this->atom->getCharge()->getChargeSize();
        $this->invariant .= $this->hydrogensCount();
    }

    private function protonNumber() {
        return $this->addZero($this->atom->getProtons());
    }

    private function actualBindingsWithZero() {
        return $this->addZero($this->actualBindings());
    }

    private function addZero($number) {
        return $number < 10 ? '0' . $number : $number;
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
    public function getInvariant() {
        return $this->invariant;
    }

    /**
     * @return mixed
     */
    public function getRank() {
        return $this->rank;
    }

    /**
     * @return Bond[]
     */
    public function getBonds(): array {
        return $this->arBonds;
    }

    /**
     * @param int $rank
     */
    public function setRank(int $rank) {
        $this->rank = $rank;
    }

    /**
     * @param $invariant
     */
    public function setInvariant($invariant) {
        $this->invariant = $invariant;
    }

    /**
     * @return int
     */
    public function getLastRank(): int {
        return $this->lastRank;
    }

    /**
     * @param int $lastRank
     */
    public function setLastRank(int $lastRank): void {
        $this->lastRank = $lastRank;
    }

}
