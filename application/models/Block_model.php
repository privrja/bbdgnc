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
        $this->db->limit('1');
        $query = $this->db->get_where(self::TABLE_NAME, array('usmiles' => $usmiles));
        return $query->row_array();
    }

    public function findBlockBySmiles(string $smiles) {
        $this->db->limit('1');
        $query = $this->db->get_where(self::TABLE_NAME, array('smiles' => $smiles));
        return $query->row_array();
    }

    public function findByAcronym($acronym) {
        $query = $this->db->get_where($this->getTableName(), array('acronym' => $acronym));
        return $query->row_array();
    }

    /**
     * Insert blocks to database
     * @param BlockTO[] $arTos array with blocks
     */
    public function insertMore(array $arTos) {
        $this->db->insert_batch(self::TABLE_NAME, $arTos);
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
