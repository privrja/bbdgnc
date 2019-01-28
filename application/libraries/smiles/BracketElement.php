<?php

namespace Bbdgnc\Smiles;

class BracketElement extends Element {

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
        assert($hydrogens >= 0);
        $this->charge = $charge;
        $this->hydrogens = $hydrogens;
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
    public function elementSmiles() {
        $smiles =  '[' . $this->name;
        if ($this->hydrogens > 0) {
            $smiles .= 'H' . $this->hydrogens;
        }
        if (!$this->charge->isZero()) {
            $smiles .= $this->charge->getCharge();
        }
        return $smiles . ']';
    }

}
