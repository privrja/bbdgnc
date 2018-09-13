<?php

class Settings extends CI_Controller {

    /**
     * Settings constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->load->helper("form");
        $this->load->library("form_validation");
        $this->load->helper('url');
    }

    public function index() {
        $this->load->view('templates/header');
        $this->load->view('settings/main');
        $this->load->view('templates/footer');
    }

//    public function colors() {
//        $this->form_validation->set_rules('background', 'Background color', 'required');
//        $this->form_validation->set_rules('menu', 'Menu color', 'required');
//        $this->form_validation->set_rules('font', 'Font color', 'required');
//
//        if ($this->form_validation->run() === false) {
//            $this->load->view('templates/header');
//            $this->load->view('settings/main');
//            $this->load->view('templates/footer');
//
//        } else {
//
////            echo $this->input->post('background');
//
//            /** save colors */
////            $this->news_model->set_news();
//            redirect("land");
//        }
//
//
//    }

}