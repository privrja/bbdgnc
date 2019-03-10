<?php

use Bbdgnc\Base\CrudModel;

class Modification_model extends CrudModel {

    const TABLE_NAME = 'modification';

    /**
     * Get table name in database
     * @return string table name in database
     */
    protected function getTableName(): string {
        return self::TABLE_NAME;
    }

    public function findByName($name) {
        $query = $this->db->get_where($this->getTableName(), array('name' => $name));
        return $query->row_array();
    }

}
