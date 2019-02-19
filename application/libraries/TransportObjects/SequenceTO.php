<?php

namespace Bbdgnc\TransportObjects;

use Bbdgnc\Enum\SequenceTypeEnum;
use Bbdgnc\Finder\Enum\ServerEnum;

class SequenceTO {

    public $database = ServerEnum::PUBCHEM;

    public $name = "";

    public $smiles = "";

    public $formula = "";

    public $mass = "";

    public $identifier = "";

    public $sequence = "";

    public $sequenceType = SequenceTypeEnum::LINEAR;

    public function asSequence() {
        return [
            'type' => $this->sequenceType,
            'name' => $this->name,
            'formula' => $this->formula,
            'mass' => $this->mass,
            'sequence' => $this->sequence,
            'smiles' => $this->smiles,
            'usmiles' => $this->usmiles,
            'database' => $this->database,
            'identifier' => $this->identifier,
        ];
    }

}