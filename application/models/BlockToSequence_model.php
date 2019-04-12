<?php

use Bbdgnc\Base\CrudModel;

class BlockToSequence_model extends CrudModel {

    public const TABLE_NAME = "b2s";

    /**
     * Get table name in database
     * @return string table name in database
     */
    protected function getTableName(): string {
        return self::TABLE_NAME;
    }

    public function findBlockUsage($blockId) {
        $this->db->from($this->getTableName());
        $this->db->where("block_id", $blockId);
        $query = $this->db->get();
        return $query->result_array();
    }

}
