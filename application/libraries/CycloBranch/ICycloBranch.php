<?php

namespace Bbdgnc\CycloBranch;

interface ICycloBranch {

    public function import(string $filePath);

    public function export();

}
