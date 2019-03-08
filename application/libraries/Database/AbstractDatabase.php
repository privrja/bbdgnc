<?php

namespace Bbdgnc\Database;

use CI_Controller;

abstract class AbstractDatabase {

    protected $controller;

    /**
     * AbstractDatabase constructor.
     * @param CI_Controller $controller
     */
    public function __construct(CI_Controller $controller) {
        $this->controller = $controller;
    }


}