<?php

namespace Bbdgnc\TransportObjects;

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
    public function __construct(string $name, string $formula, $mass, bool $cTerminal, bool $nTerminal) {
        $this->name = $name;
        $this->formula = $formula;
        $this->mass = $mass;
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
