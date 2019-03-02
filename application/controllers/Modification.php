<?php

use Bbdgnc\Base\HelperEnum;
use Bbdgnc\Base\ModelEnum;

class Modification extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(ModelEnum::MODIFICATION_MODEL);
        $this->load->helper([HelperEnum::HELPER_URL, HelperEnum::HELPER_FORM]);
    }

    public function index() {
        $data['modifications'] = $this->modification_model->findAll();
        $this->load->view('templates/header');
        $this->load->view('modifications/index', $data);
        $this->load->view('templates/footer');
    }


}
