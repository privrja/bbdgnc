<?php

namespace Bbdgnc\TransportObjects;

use Bbdgnc\Enum\SequenceTypeEnum;
use Bbdgnc\Finder\Enum\ServerEnum;

class SequenceTO implements IEntity {
    const TABLE_NAME = self::SEQUENCE;
    const TYPE = 'type';
    const NAME = 'name';
    const FORMULA = 'formula';
    const MASS = 'mass';
    const SEQUENCE = 'sequence';
    const SMILES = 'smiles';
    const DATABASE = 'database';
    const IDENTIFIER = 'identifier';
    const C_MODIFICATION_ID = 'c_modification_id';
    const N_MODIFICATION_ID = 'n_modification_id';
    const B_MODIFICATION_ID = 'b_modification_id';
    const DECAYS = 'decays';

    public $database = ServerEnum::PUBCHEM;

    public $name = "";

    public $smiles = "";

    public $formula = "";

    public $mass = "";

    public $identifier;

    public $sequence = "";

    public $sequenceType = SequenceTypeEnum::LINEAR;

    public $decays = "";

    public $nModification;

    public $cModification;

    public $bModification;

    /**
     * SequenceTO constructor.
     * @param int $database
     * @param string $name
     * @param string $smiles
     * @param string $formula
     * @param string $mass
     * @param string $identifier
     * @param string $sequence
     * @param int $sequenceType
     */
    public function __construct($database, string $name, $smiles, string $formula, $mass, $identifier, string $sequence, int $sequenceType) {
        if ($database !== null && $identifier !== "" && $identifier !== null) {
            $this->database = $database;
            $this->identifier = $identifier;
        }
        $this->name = $name;
        $this->smiles = $smiles;
        $this->formula = $formula;
        $this->mass = $mass;
        $this->sequence = $sequence;
        $this->sequenceType = $sequenceType;
    }


    public function asEntity() {
        if ($this->nModification === "") {
            $this->nModification = null;
        }

        if ($this->cModification === "") {
            $this->cModification = null;
        }

        if ($this->bModification === "") {
            $this->bModification = null;
        }

        if ($this->decays === "undefined" || $this->decays === "null") {
            $this->decays = "";
        }

        return [
            self::TYPE => $this->sequenceType,
            self::NAME => $this->name,
            self::FORMULA => $this->formula,
            self::MASS => $this->mass,
            self::SEQUENCE => $this->sequence,
            self::SMILES => $this->smiles,
            self::DATABASE => $this->database,
            self::IDENTIFIER => $this->identifier,
            self::DECAYS => $this->decays,
            self::C_MODIFICATION_ID => $this->cModification,
            self::N_MODIFICATION_ID => $this->nModification,
            self::B_MODIFICATION_ID => $this->bModification,
        ];
    }

}
