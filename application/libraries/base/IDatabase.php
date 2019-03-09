<?php

namespace Bbdgnc\Base;

interface IDatabase {

    public function findAllPaging($start);

    public function findAllPagingCount();

    public function findById($id);

    public function update($id, $to);

    public function insert($to);
}
