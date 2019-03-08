<?php

use Bbdgnc\Base\CommonConstants;
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

    public function findAllMergeByFormula() {
        $this->db->from($this->getTableName());
        $this->db->order_by('formula', 'asc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function findGroupByFormulaCount() {
        $this->db->select('formula');
        $this->db->from($this->getTableName());
        $this->db->group_by('residue');
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function findGroupByFormula(int $start) {
        $this->db->select('residue');
        $this->db->from($this->getTableName());
        $this->db->group_by('residue');
        $this->db->limit(CommonConstants::PAGING, $start);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function findByFormula(string $formula) {
        $query = $this->db->get_where($this->getTableName(), ['residue' => $formula]);
        return $query->result_array();
    }

}
