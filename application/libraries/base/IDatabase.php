<?php

namespace Bbdgnc\Base;

interface IDatabase {

    public function findAllPaging($start, Query $query);

    public function findAllPagingCount(Query $query);

    public function findById($id);

    public function update($id, $to);

    public function insert($to);

    public function insertMore(array $tos);

    public function startTransaction();

    public function endTransaction();

    public function commit();

    public function rollback();
}
