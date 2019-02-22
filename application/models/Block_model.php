<?php

use Bbdgnc\TransportObjects\BlockTO;

class Block_model extends CI_Model {

    const TABLE_NAME = 'block';

    public function __construct() {
        $this->load->database();
    }

    /**
     * Get all blocks from database
     * @return array
     */
    public function getAll() {
        $query = $this->db->get(self::TABLE_NAME);
        return $query->result_array();
    }

    public function findById($id) {
        $query = $this->db->get_where(self::TABLE_NAME, array('id' => $id));
        return $query->result_array();
    }

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
     * Insert block to database
     * @param BlockTO $blockTO
     * @return mixed id of new record
     */
    public function insert(BlockTO $blockTO) {
        $this->db->insert(self::TABLE_NAME, $blockTO->asBlock());
        return $this->db->insert_id();
    }

    /**
     * Insert blocks to database
     * @param BlockTO[] $blocks array with blocks
     */
    public function insertMore(array $blocks) {
        $this->db->insert_batch(self::TABLE_NAME, $blocks);
    }

    // TODO try to set this in super class
    public function startTransaction() {
        $this->db->trans_start();
    }

    public function endTransaction() {
        $this->db->trans_complete();
    }

}
