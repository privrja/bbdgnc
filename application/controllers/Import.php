<?php

use Bbdgnc\Base\HelperEnum;
use Bbdgnc\Base\ModelEnum;
use Bbdgnc\CycloBranch\CycloBranch;
use Bbdgnc\Enum\Front;

class Import extends CI_Controller {

    /**
     * Import constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->load->helper(HelperEnum::HELPER_FORM, HelperEnum::HELPER_URL);
        $this->load->model(ModelEnum::BLOCK_MODEL);
        $this->load->model(ModelEnum::SEQUENCE_MODEL);
        $this->load->model(ModelEnum::MODIFICATION_MODEL);
        $this->load->model(ModelEnum::BLOCK_TO_SEQUENCE_MODEL);
    }

    /**
     * Page import
     * url: /import
     */
    public function index() {
        $this->load->view(Front::TEMPLATES_HEADER);
        $this->load->view('import/index', ['error' => '']);
        $this->load->view(Front::TEMPLATES_FOOTER);
    }

    /**
     * Page after import, uploading file
     */
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
            $type = $this->input->post('importType');
            $this->imp($uploadData['full_path'], $type);
            $data = ['upload_data' => $uploadData];
            $this->load->view('templates/header');
            $this->load->view('import/upload', $data);
            $this->load->view('templates/footer');
        }
    }

    /**
     * File processing
     * @param string $filePath path to uploaded file
     * @param int $type block, modification or sequence?
     * @see ImportEnum
     */
    private function imp(string $filePath, int $type) {
        $cycloBranch = new CycloBranch($type, $this);
        $cycloBranch->import($filePath);
    }

}
