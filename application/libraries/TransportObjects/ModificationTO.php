<?php

namespace Bbdgnc\TransportObjects;

class ModificationTO {

    public $name;

    public $formula;

    public $mass;

    public $cTerminal;

    public $nTerminal;

    public function asModification() {
        return [
            'name' => $this->name,
            'formula' => $this->formula,
            'mass' => $this->mass,
            'nterminal' => $this->nTerminal,
            'cterminal' => $this->cTerminal,
        ];
    }

}