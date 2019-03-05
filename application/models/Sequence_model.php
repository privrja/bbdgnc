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

}
