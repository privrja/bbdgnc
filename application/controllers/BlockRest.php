<?php

use Bbdgnc\Base\ModelEnum;
use Bbdgnc\Database\BlockDatabase;

class BlockRest extends CI_Controller {

    private $database;

    /**
     * BlockRest constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->load->model(ModelEnum::BLOCK_MODEL);
        $this->database = new BlockDatabase($this);
    }

    public function index($id = 1) {
        $data = [];
        $data['json'] = json_encode($this->database->findById($id));
        $this->load->view('rest/index', $data);
    }

}
