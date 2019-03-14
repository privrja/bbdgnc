<?php

namespace Bbdgnc\Database;

use Bbdgnc\Base\IDatabase;
use CI_Controller;

abstract class AbstractDatabase implements IDatabase {

    protected $controller;

    /**
     * AbstractDatabase constructor.
     * @param CI_Controller $controller
     */
    public function __construct($controller) {
        $this->controller = $controller;
    }

}
