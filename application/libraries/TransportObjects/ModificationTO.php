<?php

namespace Bbdgnc\TransportObjects;

class ModificationTO implements IEntity {

    public $name;

    public $formula;

    public $mass;

    public $cTerminal;

    public $nTerminal;

    /**
     * ModificationTO constructor.
     * @param $name
     * @param $formula
     * @param $mass
     * @param $cTerminal
     * @param $nTerminal
     */
    public function __construct($name, $formula, $mass, $cTerminal, $nTerminal) {
        $this->name = $name;
        $this->formula = $formula;
        $this->mass = $mass;
        $this->cTerminal = $cTerminal;
        $this->nTerminal = $nTerminal;
    }

    public function asEntity() {
        return [
            'name' => $this->name,
            'formula' => $this->formula,
            'mass' => $this->mass,
            'nterminal' => $this->nTerminal,
            'cterminal' => $this->cTerminal,
        ];
    }

}