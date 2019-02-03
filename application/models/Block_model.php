<?php

use Bbdgnc\TransportObjects\BlockTO;

class Block_model extends CI_Model {

    /**
     * Block_model constructor.
     */
    public function __construct() {
        $this->load->database();
    }

    public function getAll() {
        $query = $this->db->get('block');
        return $query->result_array();
    }

    public function getBlockByUniqueSmiles(string $usmiles) {
        $query = $this->db->get_where('block', array('usmiles' => $usmiles));
        $result = $query->result_array();
        if (empty($result)) {
            return [];
        }
        return $result[0];
    }


    public function insertBlock(BlockTO $blockTO) {

    }

}
