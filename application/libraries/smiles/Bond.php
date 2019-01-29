<?php

namespace Bbdgnc\Smiles;

use Bbdgnc\Smiles\Enum\BondTypeEnum;

class Bond {

    /** @var int $nodeNumber */
    private $nodeNumber;

    /** @var int $bondType */
    private $bondType;

    /**
     * Bound constructor.
     * @param $nodeNumber
     * @param $bondType
     */
    public function __construct(int $nodeNumber, string $bondType) {
        $this->nodeNumber = $nodeNumber;
        $this->bondType = BondTypeEnum::$values[$bondType];
    }

    /**
     * @param int $bondType
     * @see BondTypeEnum
     */
    public function setBondType(int $bondType) {
        $this->bondType = $bondType;
    }

    /**
     * @return mixed
     */
    public function getNodeNumber() {
        return $this->nodeNumber;
    }

    /**
     * @return mixed
     */
    public function getBondType() {
        return $this->bondType;
    }

    public function getBondTypeString() {
        return BondTypeEnum::$backValues[$this->bondType];
    }

}
