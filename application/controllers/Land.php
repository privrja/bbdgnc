<?php

class Land extends CI_Controller {

    /**
     * Land constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->load->helper("form");
    }

    public function index() {
        $this->load->library("form_validation");

        $data['title'] = 'Block';

        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('text', 'Text', 'required');

        if ($this->form_validation->run() === false) {
            $this->load->view('templates/header');
            $this->load->view('pages/main');
            $this->load->view('templates/footer');

        } else {
            $this->news_model->set_news();
            $this->load->view('pages/block');
        }
    }


}