<?php

use Bbdgnc\TransportObjects\SequenceTO;

class Sequence_model extends CI_Model {

    const TABLE_NAME = 'sequence';

    public function __construct() {
        $this->load->database();
    }

    public function getAll() {
        $query = $this->db->get(self::TABLE_NAME);
        return $query->result_array();
    }

    public function insert(SequenceTO $sequenceTO) {
        $this->db->insert(self::TABLE_NAME, $sequenceTO->asSequence());
    }

}
