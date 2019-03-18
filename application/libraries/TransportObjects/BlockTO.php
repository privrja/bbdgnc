<?php

namespace Bbdgnc\TransportObjects;

use Bbdgnc\Base\FormulaHelper;
use Bbdgnc\Base\Logger;
use Bbdgnc\Enum\ComputeEnum;
use Bbdgnc\Enum\LoggerEnum;
use Bbdgnc\Exception\IllegalArgumentException;
use Bbdgnc\Smiles\Enum\LossesEnum;
use Bbdgnc\Smiles\Graph;

class BlockTO implements IEntity {

    const TABLE_NAME = 'block';
    const NAME = 'name';
    const ACRONYM = 'acronym';
    const RESIDUE = 'residue';
    const MASS = 'mass';
    const LOSSES = 'losses';
    const SMILES = 'smiles';
    const USMILES = 'usmiles';
    const DATABASE = 'database';
    const IDENTIFIER = 'identifier';

    public $id = 0;

    public $databaseId;

    public $name = "";

    public $acronym = "";

    public $formula = "";

    public $losses = "";

    public $mass = 0;

    public $smiles = "";

    public $uniqueSmiles;

    public $database;

    public $identifier;

    /**
     * BlockTO constructor.
     * @param int $id
     * @param string $name
     * @param string $acronym
     * @param string $smiles
     * @param int $compute
     * @see ComputeEnum
     */
    public function __construct(int $id, $name, $acronym, $smiles, int $compute = ComputeEnum::FORMULA_MASS, $strFormula = '') {
        $this->id = $id;
        $this->name = $name;
        $this->acronym = $acronym;
        $this->smiles = $smiles;
        if ($smiles !== "") {
            switch ($compute) {
                case ComputeEnum::FORMULA_MASS:
                    $this->computeFormulaAndMass($strFormula);
                    break;
                case ComputeEnum::UNIQUE_SMILES:
                    $this->computeUniqueSmiles();
                    break;
                case ComputeEnum::ALL:
                    $this->computeAll();
                    break;
            }
        }
    }

    public function computeAll() {
        $graph = new Graph($this->smiles);
        $this->uniqueSmiles = $graph->getUniqueSmiles();
        $this->formula = $graph->getFormula(LossesEnum::H2O);
        $this->mass = FormulaHelper::computeMass($this->formula);
    }

    public function computeUniqueSmiles() {
        $graph = new Graph($this->smiles);
        $this->uniqueSmiles = $graph->getUniqueSmiles();
    }

    public function computeFormulaAndMass($strFormula = '') {
        $this->computeFormula($strFormula);
        $this->computeMass();
    }

    public function computeFormula($strFormula = '') {
        try {
            $this->formula = FormulaHelper::formulaFromSmiles($this->smiles, LossesEnum::H2O);
        } catch (IllegalArgumentException $e) {
            $this->formula = FormulaHelper::formulaWithLosses($strFormula, LossesEnum::H2O);
        }
    }

    public function computeMass() {
        try {
            $this->mass = FormulaHelper::computeMass($this->formula);
        } catch (IllegalArgumentException $exception) {
            Logger::log(LoggerEnum::ERROR, $exception->getMessage() . " " . $exception->getTraceAsString());
        }
    }

    public function asEntity() {
        return [
            self::NAME => $this->name,
            self::ACRONYM => $this->acronym,
            self::RESIDUE => $this->formula,
            self::MASS => $this->mass,
            self::LOSSES => $this->losses,
            self::SMILES => $this->smiles,
            self::USMILES => $this->uniqueSmiles,
            self::DATABASE => $this->database,
            self::IDENTIFIER => $this->identifier,
        ];
    }

}
