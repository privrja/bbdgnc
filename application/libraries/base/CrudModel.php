<?php

namespace Bbdgnc\Base;

use Bbdgnc\Exception\UniqueConstraintException;
use Bbdgnc\TransportObjects\IEntity;
use CI_Model;


/**
 * Class CrudModel
 * abstract class with default methods to access database
 * CRUD methods
 * @package Bbdgnc\Base
 */
abstract class CrudModel extends CI_Model {

    const ID = 'id';

    public function __construct() {
        $this->load->database();
    }

    /**
     * Get table name in database
     * @return string table name in database
     */
    protected abstract function getTableName(): string;

    /**
     * Get all entities from database
     * @param Query $query
     * @return array
     */
    public function findAll(Query $query) {
        $query->applyQuery($this);
        $query = $this->db->get($this->getTableName());
        return $query->result_array();
    }

    /**
     * Get count of entities in database reduced by query, needed for paging
     * @param Query $query
     * @return int
     */
    public function findAllPagingCount(Query $query) {
        $query->applyQuery($this);
        $this->db->from($this->getTableName());
        $query = $this->db->get();
        return $query->num_rows();
    }

    /**
     * Get entities in database reduced by query
     * @param Query $query
     * @return array
     */
    public function findAllPaging($start, Query $query) {
        $query->applyQuery($this);
        $this->db->from($this->getTableName());
        $this->db->limit(CommonConstants::PAGING, $start);
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Find entity in database bu id
     * @param $id
     * @return array
     */
    public function findById($id) {
        $query = $this->db->get_where($this->getTableName(), array(self::ID => $id));
        return $query->row_array();
    }

    /**
     * Insert entity to database
     * @param IEntity $entity
     * @return mixed id of new record
     * @throws UniqueConstraintException
     */
    public function insert(IEntity $entity) {
        if (!$this->db->insert($this->getTableName(), $entity->asEntity())) {
            $error = $this->db->error(); // Has keys 'code' and 'message'
            if ($error['code'] === "23000/19") {
                throw new UniqueConstraintException("On entity: " . implode(" ", $entity->asEntity()));
            }
        }
        return $this->db->insert_id();
    }

    /**
     * Insert blocks to database
     * @param array $arTos
     * @return int last inserted id
     */
    public function insertMore(array $arTos) {
        $this->db->insert_batch($this->getTableName(), $arTos);
        return $this->db->insert_id();
    }

    /**
     * Edit entity with specified id
     * @param $id int
     * @param IEntity $entity
     * @throws UniqueConstraintException
     */
    public function update($id, IEntity $entity) {
        $this->db->where(self::ID, $id);
        if (!$this->db->update($this->getTableName(), $entity->asEntity())) {
            $error = $this->db->error(); // Has keys 'code' and 'message'
            if ($error['code'] === "23000/19") {
                throw new UniqueConstraintException("On entity: " . implode(" ", $entity->asEntity()));
            }
        }
    }

    /**
     * Delete entity with specified id
     * @param $id
     */
    public function delete($id) {
        $this->db->delete($this->getTableName(), array('id' => $id));
    }

    /**
     * Start database transaction
     */
    public function startTransaction() {
        $this->db->trans_start();
    }

    /**
     * Finish database transaction
     */
    public function endTransaction() {
        $this->db->trans_complete();
    }

    /**
     * Commit transaction
     */
    public function commit() {
        $this->db->trans_commit();
    }

    /**
     * Rollback transaction
     */
    public function rollback() {
        $this->db->trans_rollback();
    }

    /**
     * Delete all entities from database
     */
    public function deleteAll() {
        $this->db->empty_table($this->getTableName());
    }

}
