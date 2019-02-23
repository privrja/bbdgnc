<?php

namespace Bbdgnc\TransportObjects;

use Bbdgnc\Base\FormulaHelper;
use Bbdgnc\Enum\ComputeEnum;
use Bbdgnc\Enum\LoggerEnum;
use Bbdgnc\Exception\IllegalArgumentException;
use Bbdgnc\Smiles\Enum\LossesEnum;
use Bbdgnc\Smiles\Graph;

class BlockTO implements IEntity {

    public $id = 0;

    public $name = "";

    public $acronym = "";

    public $formula = "";

    public $losses = "";

    public $mass = 0;

    public $smiles = "";

    public $uniqueSmiles;

    /** @var ReferenceTO $reference */
    public $reference;

    /**
     * BlockTO constructor.
     * @param int $id
     * @param string $name
     * @param string $acronym
     * @param string $smiles
     * @param int $compute
     * @see ComputeEnum
     */
    public function __construct(int $id, $name, $acronym, $smiles, int $compute = ComputeEnum::FORMULA_MASS) {
        $this->id = $id;
        $this->name = $name;
        $this->acronym = $acronym;
        $this->smiles = $smiles;
        if (!$smiles == "") {
            switch ($compute) {
                case ComputeEnum::FORMULA_MASS:
                    $this->computeFormulaAndMass();
                    break;
                case ComputeEnum::UNIQUE_SMILES:
                    $this->computeUniqueSmiles();
                    break;
                case ComputeEnum::ALL:
                    $this->computeAll();
                    break;
            }
        }
        $this->reference = new ReferenceTO();
    }

    private function computeAll() {
        $graph = new Graph($this->smiles);
        $this->uniqueSmiles = $graph->getUniqueSmiles();
        $this->formula = $graph->getFormula(LossesEnum::H2O);
        // TODO tohle by šlo asi přesunout do grafu, tam by to možná šlo spočítat bez formule, zalezi jestli by to bylo potřeba
        $this->mass = FormulaHelper::computeMass($this->formula);
    }

    private function computeUniqueSmiles() {
        $graph = new Graph($this->smiles);
        $this->uniqueSmiles = $graph->getUniqueSmiles();
    }

    private function computeFormulaAndMass() {
        $this->formula = FormulaHelper::formulaFromSmiles($this->smiles, LossesEnum::H2O);
        try {
            $this->mass = FormulaHelper::computeMass($this->formula);
        } catch (IllegalArgumentException $exception) {
            log_message(LoggerEnum::ERROR, $exception->getMessage());
        }
    }

    public function asEntity() {
        return ['name' => $this->name, 'acronym' => $this->acronym, 'residue' => $this->formula, 'mass' => $this->mass, 'smiles' => $this->smiles, 'usmiles' => $this->uniqueSmiles];
    }

}