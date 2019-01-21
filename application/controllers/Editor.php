<?php

use Bbdgnc\Enum\Front;

class Editor extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper("form");
        $this->load->helper('url');
    }

    public function index() {
        $inputSmiles = $this->input->post(Front::BLOCKS_BLOCK_SMILES);
        $editorInput = $this->input->post(Front::EDITOR_INPUT);
        $inputSmile = $this->input->post(Front::CANVAS_INPUT_SMILE);

        // TODO value check

        $data[Front::BLOCKS_BLOCK_SMILES] = $inputSmiles;
        $data[Front::EDITOR_INPUT] = $editorInput;
        $data[Front::CANVAS_INPUT_SMILE] = $inputSmile;
        $this->load->view('templates/header');
        $this->load->view('editor/index', $data);
        $this->load->view('templates/footer');
    }

}