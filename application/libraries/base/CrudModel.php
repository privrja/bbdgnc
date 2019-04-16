<?php

namespace Bbdgnc\Base;

use Bbdgnc\Exception\UniqueConstraintException;
use Bbdgnc\TransportObjects\IEntity;
use CI_Model;

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

    public function findAllPagingCount(Query $query) {
        $query->applyQuery($this);
        $this->db->from($this->getTableName());
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function findAllPaging($start, Query $query) {
        $query->applyQuery($this);
        $this->db->from($this->getTableName());
        $this->db->limit(CommonConstants::PAGING, $start);
        $query = $this->db->get();
        return $query->result_array();
    }

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
     * @return
     */
    public function insertMore(array $arTos) {
        $this->db->insert_batch($this->getTableName(), $arTos);
        return $this->db->insert_id();
    }

    /**
     * @param $id
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
     * Commit
     */
    public function commit() {
        $this->db->trans_commit();
    }

    /**
     * Rollback
     */
    public function rollback() {
        $this->db->trans_rollback();
    }

    public function deleteAll() {
        $this->db->empty_table($this->getTableName());
    }

}
