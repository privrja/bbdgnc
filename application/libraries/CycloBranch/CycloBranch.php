<?php

namespace Bbdgnc\CycloBranch;

use CI_Controller;

class CycloBranch {

    private $controller;

    private $type;

    /**
     * CycloBranch constructor.
     * @param CI_Controller $controller
     * @param int $type
     */
    public function __construct(int $type, CI_Controller $controller) {
        $this->type = $type;
        $this->controller = $controller;
    }

    public function import(string $filePath) {
        $cycloBranch = ImportTypeFactory::getCycloBranch($this->type, $this->controller);
        $cycloBranch->import($filePath);
    }

    public function export() {

    }

}
