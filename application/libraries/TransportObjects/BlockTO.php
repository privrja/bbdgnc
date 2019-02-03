<?php

namespace Bbdgnc\TransportObjects;

use Bbdgnc\Base\FormulaHelper;
use Bbdgnc\Enum\ComputeEnum;
use Bbdgnc\Enum\LoggerEnum;
use Bbdgnc\Exception\IllegalArgumentException;
use Bbdgnc\Smiles\Enum\LossesEnum;

class BlockTO {

    public $id = 0;

    public $name = "";

    public $acronym = "";

    public $formula = "";

    public $losses = "";

    public $mass = 0;

    public $smiles = "";

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
    public function __construct(int $id, $name, $acronym, $smiles, int $compute = ComputeEnum::YES) {
        $this->id = $id;
        $this->name = $name;
        $this->acronym = $acronym;
        $this->smiles = $smiles;
        if ($compute === ComputeEnum::YES) {
            $this->formula = FormulaHelper::formulaFromSmiles($smiles, LossesEnum::H2O);
            try {
                $this->mass = FormulaHelper::computeMass($this->formula);
            } catch (IllegalArgumentException $exception) {
                log_message(LoggerEnum::ERROR, $exception->getMessage());
            }
        }
        $this->reference = new ReferenceTO();
    }


}