<?php

namespace Bbdgnc\Base;

use Bbdgnc\Exception\UniqueConstraintException;
use Bbdgnc\TransportObjects\IEntity;
use CI_Model;
use mysql_xdevapi\Exception;

abstract class CrudModel extends CI_Model {

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
     * @return array
     */
    public function findAll() {
        $query = $this->db->get($this->getTableName());
        return $query->result_array();
    }

    public function findById($id) {
        $query = $this->db->get_where($this->getTableName(), array('id' => $id));
        // TODO result only one result
        return $query->result_array();
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
     */
    public function insertMore(array $blocks) {
        $this->db->insert_batch($this->getTableName(), $blocks);
    }


    public function update() {
        // TODO
    }

    public function delete() {
        // TODO
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

}
