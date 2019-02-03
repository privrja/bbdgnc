<?php

interface ICycloBranch {

    public function import(string $filePath);

    public function export();

}
