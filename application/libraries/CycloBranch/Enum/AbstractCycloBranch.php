<?php

abstract class AbstractCycloBranch implements ICycloBranch {
    /**
     * @var CI_Controller
     */
    private $controller;

    /**
     * AbstractCycloBranch constructor.
     * @param CI_Controller $controller
     */
    public function __construct(CI_Controller $controller) {
        $this->controller = $controller;
    }

    public function import(string $filePath) {
        $handle = fopen($filePath, 'r');
        if (!$handle) {
            return;
        }
        while (($line = fgets($handle)) !== false) {
            $this->parseLine($line);
        }
        fclose($handle);
        unlink($filePath);
    }

    public abstract function export();

    protected abstract function parseLine(string $line);

}
