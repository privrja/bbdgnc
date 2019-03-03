<?php

use Bbdgnc\Base\HelperEnum;
use Bbdgnc\Base\ModelEnum;
use Bbdgnc\Enum\Front;

class Sequence extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(ModelEnum::SEQUENCE_MODEL);
        $this->load->helper([HelperEnum::HELPER_URL, HelperEnum::HELPER_FORM]);
    }

    public function index() {
        $data['sequences'] = $this->sequence_model->findAll();
        $this->load->view(Front::TEMPLATES_HEADER);
        $this->load->view('sequences/index', $data);
        $this->load->view(Front::TEMPLATES_FOOTER);
    }

    public function detail($id = 1) {
        $data['sequence'] = $this->sequence_model->findById($id);
        $this->load->view(Front::TEMPLATES_HEADER);
        $this->load->view('sequences/detail', $data);
        $this->load->view(Front::TEMPLATES_FOOTER);
    }

}
