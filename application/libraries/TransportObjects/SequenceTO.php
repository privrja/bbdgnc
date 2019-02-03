<?php

namespace Bbdgnc\TransportObjects;

use Bbdgnc\Enum\SequenceTypeEnum;
use Bbdgnc\Finder\Enum\FindByEnum;
use Bbdgnc\Finder\Enum\ServerEnum;

class SequenceTO {

    public $database = ServerEnum::PUBCHEM;

    public $search = FindByEnum::NAME;

    public $name = "";

    public $match = false;

    public $smiles = "";

    public $formula = "";

    public $mass = "";

    public $deflection = "";

    public $identifier = "";

    public $blockCount = 0;

    public $sequenceType = SequenceTypeEnum::LINEAR;

    public $nTerminalModification = "";

    public $cTerminalModification = "";

    public $branchModification = "";

}