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

}