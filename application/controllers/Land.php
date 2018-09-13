<?php

class Land extends CI_Controller {

    public function index() {
        $data = array();

        $this->load->view("templates/header");
        $this->load->view("pages/main", $data);
        $this->load->view("templates/footer");
    }


}