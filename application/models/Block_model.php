<?php

use Bbdgnc\Base\CrudModel;
use Bbdgnc\TransportObjects\BlockTO;

class Block_model extends CrudModel {

    const TABLE_NAME = 'block';

    /**
     * Get blocks from database with specific unique SMILES
     * @param string $usmiles unique SMILES
     * @return array
     */
    public function getBlockByUniqueSmiles(string $usmiles) {
        $query = $this->db->get_where(self::TABLE_NAME, array('usmiles' => $usmiles));
        $result = $query->result_array();
        if (empty($result)) {
            return [];
        }
        return $result[0];
    }

    /**
     * Insert blocks to database
     * @param BlockTO[] $blocks array with blocks
     */
    public function insertMore(array $blocks) {
        $this->db->insert_batch(self::TABLE_NAME, $blocks);
    }

    /**
     * Get table name in database
     * @return string table name in database
     */
    protected function getTableName(): string {
        return self::TABLE_NAME;
    }

    public function findBlocksBySequenceId($id) {
        $this->db
            ->from($this->getTableName())
            ->join('b2s', 'b2s.block_id = block.id')
            ->where('b2s.sequence_id', $id);
        $query = $this->db->get();
        return $query->result_array();
    }

}
