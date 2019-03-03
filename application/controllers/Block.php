<?php

use Bbdgnc\Base\HelperEnum;
use Bbdgnc\Base\ModelEnum;
use Bbdgnc\Enum\Front;

class Block extends CI_Controller {

    private $errors = "";

    /**
     * Block constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->load->model(ModelEnum::BLOCK_MODEL);
        $this->load->helper([HelperEnum::HELPER_URL, HelperEnum::HELPER_FORM]);
        $this->load->library('form_validation');
    }

    public function index() {
        $data['blocks'] = $this->block_model->findAll();
        $this->load->view(Front::TEMPLATES_HEADER);
        $this->load->view('blocks/index', $data);
        $this->load->view(Front::TEMPLATES_FOOTER);
    }

    public function new() {
        $data = [];
        $this->form_validation->set_rules(Front::BLOCK_NAME, 'Name', Front::REQUIRED);
        $this->form_validation->set_rules(Front::BLOCK_ACRONYM, 'Acronym', Front::REQUIRED);
        $this->form_validation->set_rules(Front::BLOCK_FORMULA, 'Formula', Front::REQUIRED);
        if ($this->form_validation->run() === false) {
            $data[Front::ERRORS] = $this->errors;
        }

        $this->load->view(Front::TEMPLATES_HEADER);
        $this->load->view('blocks/new', $data);
        $this->load->view(Front::TEMPLATES_FOOTER);
    }

    public function add() {


    }

    public function detail($id = 1) {
        $data['block'] = $this->block_model->findById($id);
        $this->load->view(Front::TEMPLATES_HEADER);
        $this->load->view('blocks/detail', $data);
        $this->load->view(Front::TEMPLATES_FOOTER);
    }



}
