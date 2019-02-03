<?php

use Bbdgnc\Base\HelperEnum;

class Block extends CI_Controller {

    /**
     * Block constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('block_model');
        $this->load->helper(HelperEnum::HELPER_URL);
    }

    public function index() {
        $data['blocks'] = $this->block_model->getAll();
        $this->load->view('templates/header');
        $this->load->view('blocks/index', $data);
        $this->load->view('templates/footer');
    }
}