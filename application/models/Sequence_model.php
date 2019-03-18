<?php

use Bbdgnc\Base\CommonConstants;
use Bbdgnc\Base\CrudModel;
use Bbdgnc\base\Query;

class Sequence_model extends CrudModel {

    const TABLE_NAME = 'sequence';

    /**
     * Get table name in database
     * @return string table name in database
     */
    protected function getTableName(): string {
        return self::TABLE_NAME;
    }

    public function findSequenceWithModificationNamesCount(Query $query) {
        $this->sequenceWithNameModificationNames($query);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function findSequenceWithModificationNames($start, Query $query) {
        $this->sequenceWithNameModificationNames($query);
        $this->db->limit(CommonConstants::PAGING, $start);
        $query = $this->db->get();
        return $query->result_array();
    }

    private function sequenceWithNameModificationNames(Query $query) {
        $this->db->select("sequence.id, sequence.n_modification_id, sequence.c_modification_id, sequence.b_modification_id, sequence.type, sequence.name, sequence.formula, sequence.mass, sequence.sequence, sequence.database, sequence.identifier, nmod.name nname, cmod.name cname, bmod.name bname");
        $this->db->from($this->getTableName());
        $this->db->join('modification nmod', 'nmod.id = sequence.n_modification_id', 'left');
        $this->db->join('modification cmod', 'cmod.id = sequence.c_modification_id', 'left');
        $this->db->join('modification bmod', 'bmod.id = sequence.b_modification_id', 'left');
        $query->query($this);
    }

}
