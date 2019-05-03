<?php

namespace Bbdgnc\Base;

interface IDatabase {

    /**
     * Find all entities with paging
     * @param $start int starting page
     * @param Query $query query to reducing results
     * @return array
     */
    public function findAllPaging($start, Query $query);

    /**
     * Find count of all entities, needed for paging
     * @param Query $query query to reduced result
     * @return int
     */
    public function findAllPagingCount(Query $query);

    /**
     * Find entity by id
     * @param $id int
     * @return array
     */
    public function findById($id);

    /**
     * Update entity
     * @param $id int id
     * @param $to mixed new values
     */
    public function update($id, $to);

    /**
     * Delete entity
     * @param $id int
     * @param null $database for cascading deletes
     */
    public function delete($id, $database = null);

    /**
     * Insert new entity to database
     * @param $to
     * @return int id of new entity
     */
    public function insert($to);

    /**
     * Insert more than one entity
     * @param array $tos
     */
    public function insertMore(array $tos);

    /**
     * Start database transaction
     */
    public function startTransaction();

    /**
     * End database transaction
     */
    public function endTransaction();

    /**
     * Commit transaction
     */
    public function commit();

    /**
     * Rollback transaction
     */
    public function rollback();
}
