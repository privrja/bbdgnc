<?php

use Bbdgnc\Base\HelperEnum;
use Bbdgnc\Base\ModelEnum;

class Sequence extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(ModelEnum::SEQUENCE_MODEL);
        $this->load->helper([HelperEnum::HELPER_URL, HelperEnum::HELPER_FORM]);
    }

    public function index() {
        $data['sequences'] = $this->sequence_model->findAll();
        $this->load->view('templates/header');
        $this->load->view('sequences/index', $data);
        $this->load->view('templates/footer');
    }

}
