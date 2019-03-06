<?php

use Bbdgnc\Base\CrudModel;

class Sequence_model extends CrudModel {

    const TABLE_NAME = 'sequence';

    /**
     * Get table name in database
     * @return string table name in database
     */
    protected function getTableName(): string {
        return self::TABLE_NAME;
    }

//    public function findSequenceByIdWithBlocks($id) {
//        $query = $this->db
//            ->from($this->getTableName())
//            ->join('b2s', 'b2s.sequence_id = ' . $this->getTableName() . self::DOT . self::ID)
//            ->where($this->getTableName() . self::DOT . self::ID, [self::ID => $id]);
//
//        return $query->result_array();
//    }

}
