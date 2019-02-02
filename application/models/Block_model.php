<?php

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

}
