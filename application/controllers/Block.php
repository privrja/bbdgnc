<?php

use Bbdgnc\Base\HelperEnum;
use Bbdgnc\Base\ModelEnum;

class Block extends CI_Controller {

    /**
     * Block constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->load->model(ModelEnum::BLOCK_MODEL);
        $this->load->helper([HelperEnum::HELPER_URL, HelperEnum::HELPER_FORM]);
    }

    public function index() {
        $data['blocks'] = $this->block_model->findAll();
        $this->load->view('templates/header');
        $this->load->view('blocks/index', $data);
        $this->load->view('templates/footer');
    }
}
