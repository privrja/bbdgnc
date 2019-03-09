<?php

namespace Bbdgnc\Database;

class ModificationDatabase extends AbstractDatabase {

    public function findAllPaging($start) {
       return $this->controller->modification_model->findAllPaging($start);
    }

    public function findAllPagingCount() {
        return $this->controller->modification_model->findAllPagingCount();
    }
}