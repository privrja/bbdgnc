<?php

namespace Bbdgnc\Database;

class ModificationDatabase extends AbstractDatabase {

    public function findAll($page) {
       return $this->controller->modification_model->findAllPaging($page);
    }

}