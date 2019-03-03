<?php

use Bbdgnc\Base\HelperEnum;
use Bbdgnc\Base\ModelEnum;
use Bbdgnc\Enum\Front;

class Modification extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(ModelEnum::MODIFICATION_MODEL);
        $this->load->helper([HelperEnum::HELPER_URL, HelperEnum::HELPER_FORM]);
    }

    public function index() {
        $data['modifications'] = $this->modification_model->findAll();
        $this->load->view(Front::TEMPLATES_HEADER);
        $this->load->view('modifications/index', $data);
        $this->load->view(Front::TEMPLATES_FOOTER);
    }

    public function detail($id = 1) {
        $data['modification'] = $this->modification_model->findById($id);
        $this->load->view(Front::TEMPLATES_HEADER);
        $this->load->view('modifications/detail', $data);
        $this->load->view(Front::TEMPLATES_FOOTER);
    }

}
