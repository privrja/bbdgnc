<?php

namespace Bbdgnc\Database;

use Bbdgnc\Base\IDatabase;
use Bbdgnc\Base\Query;
use Bbdgnc\Base\Sortable;
use Bbdgnc\Exception\DeleteException;
use Bbdgnc\TransportObjects\ModificationTO;

/**
 * Class ModificationDatabase
 * @see IDatabase
 * @package Bbdgnc\Database
 */
class ModificationDatabase extends AbstractDatabase {

    public function findAllPaging($start, Query $query) {
        return $this->controller->modification_model->findAllPaging($start, $query);
    }

    public function findAllPagingCount(Query $query) {
        return $this->controller->modification_model->findAllPagingCount($query);
    }

    public function findAll(Query $query) {
        return $this->controller->modification_model->findAll($query);
    }

    public function findById($id) {
        return $this->controller->modification_model->findById($id);
    }

    public function findByName($name) {
        return $this->controller->modification_model->findByName($name);
    }

    public function update($id, $to) {
        $this->controller->modification_model->update($id, $to);
    }

    public function insert($to) {
        return $this->controller->modification_model->insert($to);
    }

    public function delete($id, $database = null) {
        if ($database === null) {
            throw new DeleteException();
        }

        $result = $database->findSequenceWithModificationUsage($id);
        if (empty($result)) {
            $this->controller->modification_model->delete($id);
        } else {
            $name = $result[0][ModificationTO::NAME];
            if (!isset($result[1])) {
                throw new DeleteException("Modification is used in: $name!");
            } else {
                throw new DeleteException("Modification is used in: $name and more!");
            }
        }
    }

    public function insertMore(array $tos) {
        $this->controller->modification_model->insertMore($tos);
    }

    public function startTransaction() {
        $this->controller->modification_model->startTransaction();
    }

    public function endTransaction() {
        $this->controller->modification_model->endTransaction();
    }

    public function commit() {
        $this->controller->modification_model->commit();
    }

    public function rollback() {
        $this->controller->modification_model->rollback();
    }

    public function findAllSelect() {
        $query = new Query();
        $query->addSortable(new Sortable('name'));
        $modificationsAll = $this->findAll($query);
        $modifications = ['None'];
        foreach ($modificationsAll as $modification) {
            $modifications[$modification['id']] = $modification['name'];
        }
        return $modifications;
    }
}
