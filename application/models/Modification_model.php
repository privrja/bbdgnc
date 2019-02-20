<?php

use Bbdgnc\TransportObjects\ModificationTO;

class Modification_model extends CI_Model {

    const TABLE_NAME = 'modification';

    public function __construct() {
        $this->load->database();
    }

    public function getAll() {
        $query = $this->db->get(self::TABLE_NAME);
        return $query->result_array();
    }

    public function insert(ModificationTO $modificationTO) {
        $this->db->insert(self::TABLE_NAME, $modificationTO->asModification());
    }

}
