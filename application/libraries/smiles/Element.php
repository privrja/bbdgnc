<?php

namespace Bbdgnc\Smiles;

class Element {

    private $name = "";
    private $protons = 0;
    private $bindings = 0;
    private $mass = 0;

    /**
     * Element constructor.
     * @param string $name shortcut of atom name ex.: H, C, O, N or He
     * @param int $protons number of protons
     * @param int $bindings number of typical bindings ex.: O have 2, C have 4, N have 3, ...
     * must be non negative number
     * @param $mass
     * must be positive number
     */
    public function __construct($name, $protons, $bindings, $mass) {
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

}
