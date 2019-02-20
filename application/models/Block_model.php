<?php

use Bbdgnc\TransportObjects\BlockTO;

class Block_model extends CI_Model {

    const TABLE_NAME = 'block';

    public function __construct() {
        $this->load->database();
    }

    public function getAll() {
        $query = $this->db->get(self::TABLE_NAME);
        return $query->result_array();
    }

    public function getBlockByUniqueSmiles(string $usmiles) {
        $query = $this->db->get_where(self::TABLE_NAME, array('usmiles' => $usmiles));
        $result = $query->result_array();
        if (empty($result)) {
            return [];
        }
        return $result[0];
    }

    public function insert(BlockTO $blockTO) {
        $this->db->insert(self::TABLE_NAME, $blockTO->asBlock());
        return $this->db->insert_id();
    }

    public function insertMore(array $blocks) {
        $this->db->insert_batch(self::TABLE_NAME, $blocks);
    }

}
