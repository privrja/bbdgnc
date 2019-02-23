<?php

namespace Bbdgnc\TransportObjects;

class BlockToSequenceTO implements IEntity {

    public $blockId;

    public $sequenceId;

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
            "block_id" => $this->blockId,
            "sequence_id" => $this->sequenceId
        ];
    }
}