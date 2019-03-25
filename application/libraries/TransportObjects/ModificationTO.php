<?php

namespace Bbdgnc\TransportObjects;

use Bbdgnc\Base\FormulaHelper;

class ModificationTO implements IEntity {

    const TABLE_NAME = 'modification';
    const LOSSES = 'losses';
    const NAME = 'name';
    const FORMULA = 'formula';
    const MASS = 'mass';
    const NTERMINAL = 'nterminal';
    const CTERMINAL = 'cterminal';

    public $name;

    public $formula;

    public $mass;

    public $cTerminal = false;

    public $nTerminal = false;

    /**
     * ModificationTO constructor.
     * @param $name
     * @param $formula
     * @param $mass
     * @param $cTerminal
     * @param $nTerminal
     */
    public function __construct(string $name, string $formula = '', $mass = 0, bool $cTerminal = false, bool $nTerminal = false) {
        $this->name = $name;
        $this->formula = $formula;
        if ($mass == 0) {
            if ($formula !== '') {
                $this->mass = FormulaHelper::computeMass($formula);
            } else {
                $this->mass = $mass;
            }
        } else {
            $this->mass = $mass;
        }
        $this->cTerminal = $cTerminal;
        $this->nTerminal = $nTerminal;
    }

    public function asEntity() {
        return [
            self::NAME => $this->name,
            self::FORMULA => $this->formula,
            self::MASS => $this->mass,
            self::NTERMINAL => $this->nTerminal,
            self::CTERMINAL => $this->cTerminal,
        ];
    }

}
