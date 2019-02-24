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

}
