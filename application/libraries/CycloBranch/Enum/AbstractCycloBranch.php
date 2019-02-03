<?php

abstract class AbstractCycloBranch implements ICycloBranch {
    /**
     * @var CI_Controller
     */
    protected $controller;

    /**
     * AbstractCycloBranch constructor.
     * @param CI_Controller $controller
     */
    public function __construct(CI_Controller $controller) {
        $this->controller = $controller;
    }

    public function import(string $filePath) {
        ini_set('max_execution_time', 120);
        $handle = fopen($filePath, 'r');
        if (!$handle) {
            return;
        }
        while (($line = fgets($handle)) !== false) {
            $this->parseLine($line);
        }
        fclose($handle);
        unlink($filePath);
        ini_set('max_execution_time', 30);
    }

    public abstract function export();

    protected abstract function parseLine(string $line);

}
