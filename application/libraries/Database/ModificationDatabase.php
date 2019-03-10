<?php

namespace Bbdgnc\Database;

class ModificationDatabase extends AbstractDatabase {

    public function findAllPaging($start) {
        return $this->controller->modification_model->findAllPaging($start);
    }

    public function findAllPagingCount() {
        return $this->controller->modification_model->findAllPagingCount();
    }

    public function findAll() {
        return $this->controller->modification_model->findAll();
    }

    public function findById($id) {
        return $this->controller->modification_model->findById($id);
    }

    public function update($id, $to) {
        $this->controller->modification_model->update($id, $to);
    }

    public function insert($to) {
        $this->controller->modification_model->insert($to);
    }
}