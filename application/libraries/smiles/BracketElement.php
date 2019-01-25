<?php

namespace Bbdgnc\Smiles;

class BracketElement extends Element {

    /** @var Charge $charge */
    private $charge;

    /** @var int $hydrogens */
    private $hydrogens;

    /**
     * BracketElement constructor.
     * @see Element
     * @param string $name
     * @param int $protons
     * @param int $bindings
     * @param float $mass
     * @param Charge $charge
     * @param int $hydrogens
     */
    public function __construct(string $name, int $protons, int $bindings, float $mass, Charge $charge, int $hydrogens) {
        parent::__construct($name, $protons, $bindings, $mass);
        $this->charge = $charge;
        $this->hydrogens = $hydrogens;
    }

    /**
     * @return Charge
     */
    public function getCharge(): Charge {
        return $this->charge;
    }

    /**
     * @param Charge $charge
     */
    public function setCharge(Charge $charge): void {
        $this->charge = $charge;
    }

    /**
     * @return int
     */
    public function getHydrogens(): int {
        return $this->hydrogens;
    }

    /**
     * @param int $hydrogens
     */
    public function setHydrogens(int $hydrogens): void {
        $this->hydrogens = $hydrogens;
    }

    public function getHydrogensCount($actualBindings) {
        return $this->getHydrogens();
    }

}