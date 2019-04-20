<?php

namespace Bbdgnc\TransportObjects;

class BlockToSequenceTO implements IEntity {

    const BLOCK_ID = "block_id";
    const SEQUENCE_ID = "sequence_id";
    const SORT = 'sort';

    public $blockId;

    public $sequenceId;

    public $sort;
    /**
     * BlockToSequenceTO constructor.
     * @param $blockId
     * @param $sequenceId
     */
    public function __construct($blockId, $sequenceId) {
        $this->blockId = $blockId;
        $this->sequenceId = $sequenceId;
    }

    /**
     * Map entity to array for store to database
     * @return array
     */
    function asEntity() {
        return [
            self::BLOCK_ID => $this->blockId,
            self::SEQUENCE_ID => $this->sequenceId,
            self::SORT => $this->sort
        ];
    }

}
