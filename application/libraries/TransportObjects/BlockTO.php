<?php

namespace Bbdgnc\TransportObjects;

use Bbdgnc\Base\FormulaHelper;
use Bbdgnc\Enum\ComputeEnum;
use Bbdgnc\Enum\LoggerEnum;
use Bbdgnc\Exception\IllegalArgumentException;
use Bbdgnc\Smiles\Enum\LossesEnum;
use Bbdgnc\Smiles\Graph;

class BlockTO {

    public $id = 0;

    public $name = "";

    public $acronym = "";

    public $formula = "";

    public $losses = "";

    public $mass = 0;

    public $smiles = "";

    public $uniqueSmiles = "";

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
        switch ($compute) {
            case ComputeEnum::FORMULA_MASS:
                $this->computeFormulaAndMass($smiles);
                break;
            case ComputeEnum::UNIQUE_SMILES:
                $this->computeUniqueSmiles($smiles);
                break;
            case ComputeEnum::ALL:
                $this->computeAll($smiles);
                break;
        }
        $this->reference = new ReferenceTO();
    }

    private function computeAll($smiles) {
        $graph = new Graph($smiles);
        $this->uniqueSmiles = $graph->getUniqueSmiles();
        $this->formula = $graph->getFormula(LossesEnum::H2O);
        // TODO tohle by šlo asi přesunout do grafu, tam by to možná šlo spočítat bez formule, zalezi jestli by to bylo potřeba
        $this->mass = FormulaHelper::computeMass($this->formula);
    }

    private function computeUniqueSmiles($smiles) {
        $graph = new Graph($smiles);
        $this->uniqueSmiles = $graph->getUniqueSmiles();
    }

    private function computeFormulaAndMass($smiles) {
        $this->formula = FormulaHelper::formulaFromSmiles($smiles, LossesEnum::H2O);
        try {
            $this->mass = FormulaHelper::computeMass($this->formula);
        } catch (IllegalArgumentException $exception) {
            log_message(LoggerEnum::ERROR, $exception->getMessage());
        }
    }

}