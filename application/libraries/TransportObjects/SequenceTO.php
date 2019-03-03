<?php

namespace Bbdgnc\TransportObjects;

use Bbdgnc\Enum\SequenceTypeEnum;
use Bbdgnc\Finder\Enum\ServerEnum;

class SequenceTO implements IEntity {

    public $databaseId;

    public $database = ServerEnum::PUBCHEM;

    public $name = "";

    public $smiles = "";

    public $formula = "";

    public $mass = "";

    public $identifier;

    public $sequence = "";

    public $sequenceType = SequenceTypeEnum::LINEAR;

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
    public function __construct(int $database, string $name, string $smiles, string $formula, string $mass, string $identifier, string $sequence, int $sequenceType) {
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
        return [
            'type' => $this->sequenceType,
            'name' => $this->name,
            'formula' => $this->formula,
            'mass' => $this->mass,
            'sequence' => $this->sequence,
            'smiles' => $this->smiles,
            'database' => $this->database,
            'identifier' => $this->identifier,
            'c_modification_id' => $this->cModification,
            'n_modification_id' => $this->nModification,
            'b_modification_id' => $this->bModification,
        ];
    }

}