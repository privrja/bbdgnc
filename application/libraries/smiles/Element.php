<?php

namespace Bbdgnc\Smiles;

class Element {

    protected $name = "";
    protected $protons = 0;
    protected $bindings = 0;
    protected $mass = 0;

    /**
     * Element constructor.
     * @param string $name shortcut of atom name ex.: H, C, O, N or He
     * @param int $protons number of protons
     * must be non negative number
     * @param int $bindings number of typical bindings ex.: O have 2, C have 4, N have 3, ...
     * must be non negative number
     * @param float $mass
     * must be positive number
     */
    public function __construct(string $name, int $protons, int $bindings, float $mass) {
        assert($protons >= 0);
        assert($bindings >= 0);
        assert($mass > 0);
        $this->name = $name;
        $this->protons = $protons;
        $this->bindings = $bindings;
        $this->mass = $mass;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getProtons() {
        return $this->protons;
    }

    /**
     * @return int
     */
    public function getBindings() {
        return $this->bindings;
    }

    /**
     * @return int
     */
    public function getMass() {
        return $this->mass;
    }

    public function getHydrogensCount($actualBindings) {
        return $this->bindings - $actualBindings;
    }

    public function asBracketElement() {
        return new BracketElement($this->name, $this->protons, $this->bindings, $this->mass, new Charge(), 0);
    }

}
