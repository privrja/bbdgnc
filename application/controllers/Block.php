<?php

use Bbdgnc\Base\HelperEnum;
use Bbdgnc\Base\ModelEnum;
use Bbdgnc\Enum\Front;

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
        $this->load->view(Front::TEMPLATES_HEADER);
        $this->load->view('blocks/index', $data);
        $this->load->view(Front::TEMPLATES_FOOTER);
    }

    public function new() {
        $this->load->view(Front::TEMPLATES_HEADER);
        $this->load->view('blocks/new');
        $this->load->view(Front::TEMPLATES_FOOTER);
    }

    public function detail($id = 1) {
        $data['block'] = $this->block_model->findById($id);
        $this->load->view(Front::TEMPLATES_HEADER);
        $this->load->view('blocks/detail', $data);
        $this->load->view(Front::TEMPLATES_FOOTER);
    }



}
