<?php

namespace Bbdgnc\Database;

use Bbdgnc\base\Query;
use Bbdgnc\Base\Sortable;

class ModificationDatabase extends AbstractDatabase {

    public function findAllPaging($start) {
        return $this->controller->modification_model->findAllPaging($start);
    }

    public function findAllPagingCount() {
        return $this->controller->modification_model->findAllPagingCount();
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
