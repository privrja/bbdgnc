<?php

use Bbdgnc\Base\CrudModel;

class BlockToSequence_model extends CrudModel {

    public const TABLE_NAME = "b2s";

    /**
     * Get table name in database
     * @return string table name in database
     */
    protected function getTableName(): string {
        return self::TABLE_NAME;
    }

}
