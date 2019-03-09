<?php

namespace Bbdgnc\Base;

interface IDatabase {

    public function findAllPaging($start);
    public function findAllPagingCount();

}
