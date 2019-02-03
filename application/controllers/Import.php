<?php

use Bbdgnc\Base\HelperEnum;

class Import extends CI_Controller {

    /**
     * Import constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->load->helper(HelperEnum::HELPER_FORM, HelperEnum::HELPER_URL);
    }

    public function index() {
        $this->load->view('templates/header');
        $this->load->view('import/index', ['error' => '']);
        $this->load->view('templates/footer');
    }

    public function upload() {
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'txt';
        $config['max_size'] = 500;
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('userfile')) {
            $this->load->view('templates/header');
            $this->load->view('import/index', ['error' => $this->upload->display_errors()]);
            $this->load->view('templates/footer');
        } else {
            $uploadData = $this->upload->data();
            $data = ['upload_data' => $uploadData];
            $this->load->view('templates/header');
            $this->load->view('import/upload', $data);
            $this->load->view('templates/footer');
        }
    }

}
