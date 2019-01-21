<?php

namespace Bbdgnc\TransportObjects;

class BlockTO {

    public $id = 0;

    public $name = "";

    public $acronym = "";

    public $smiles = "";

    /**
     * BlockTO constructor.
     * @param int $id
     * @param string $name
     * @param string $acronym
     * @param string $smiles
     */
    public function __construct(int $id, string $name, string $acronym, string $smiles) {
        $this->id = $id;
        $this->name = $name;
        $this->acronym = $acronym;
        $this->smiles = $smiles;
    }

}