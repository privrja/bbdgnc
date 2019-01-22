<?php

namespace Bbdgnc\TransportObjects;

use Bbdgnc\Base\FormulaHelper;

class BlockTO {

    public $id = 0;

    public $name = "";

    public $acronym = "";

    public $formula = "";

    public $losses = "";

    public $mass = 0;

    public $smiles = "";

    public $reference;

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
        $this->formula = FormulaHelper::formulaFromSmiles($smiles);
        $this->mass = FormulaHelper::computeMass($this->formula);
    }


}