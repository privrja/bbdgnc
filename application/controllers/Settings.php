<?php

use Bbdgnc\Base\ModelEnum;
use Bbdgnc\Database\BlockDatabase;
use Bbdgnc\Enum\Front;

class Settings extends CI_Controller {

    /**
     * Settings constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->load->helper("form");
        $this->load->library("form_validation");
        $this->load->helper('url');
        $this->load->model(ModelEnum::BLOCK_MODEL);
        $this->load->model(ModelEnum::SEQUENCE_MODEL);
        $this->load->model(ModelEnum::MODIFICATION_MODEL);
        $this->load->model(ModelEnum::BLOCK_TO_SEQUENCE_MODEL);
    }

    public function index() {
        $this->load->view('templates/header');
        $this->load->view('settings/main');
        $this->load->view('templates/footer');
    }

    public function reset() {
        $delete = $this->input->post('delete');

        $this->form_validation->set_rules('delete', 'Delete', Front::REQUIRED);
        if ($this->form_validation->run() === false || $delete !== 'delx') {
            $this->index();
            return;
        }

        $blockDatabase = new BlockDatabase($this);
        $blockDatabase->deleteAll();

        $this->index();
    }


}