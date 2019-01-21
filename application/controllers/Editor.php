<?php

use Bbdgnc\Enum\Front;

class Editor extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper("form");
        $this->load->helper('url');
    }

    private function getLastData() {
        $arViewData = array();
        $arViewData[Front::CANVAS_INPUT_DATABASE] = $this->input->post(Front::CANVAS_INPUT_DATABASE);
        $arViewData[Front::CANVAS_INPUT_SEARCH_BY] = $this->input->post(Front::CANVAS_INPUT_SEARCH_BY);
        $arViewData[Front::CANVAS_INPUT_NAME] = $this->input->post(Front::CANVAS_INPUT_NAME);
        $arViewData[Front::CANVAS_INPUT_SMILE] = $this->input->post(Front::CANVAS_INPUT_SMILE);
        $arViewData[Front::CANVAS_INPUT_FORMULA] = $this->input->post(Front::CANVAS_INPUT_FORMULA);
        $arViewData[Front::CANVAS_INPUT_MASS] = $this->input->post(Front::CANVAS_INPUT_MASS);
        $arViewData[Front::CANVAS_INPUT_DEFLECTION] = $this->input->post(Front::CANVAS_INPUT_DEFLECTION);
        $arViewData[Front::CANVAS_INPUT_IDENTIFIER] = $this->input->post(Front::CANVAS_INPUT_IDENTIFIER);
//        $arViewData[self::ERRORS] = $this->errors;
        return $arViewData;
    }

    public function index() {
        $inputSmiles = $this->input->post(Front::BLOCKS_BLOCK_SMILES);
        $editorInput = $this->input->post(Front::EDITOR_INPUT);
        $inputSmile = $this->input->post(Front::CANVAS_INPUT_SMILE);

        // TODO value check

        $data = $this->getLastData();
        $data[Front::BLOCKS_BLOCK_SMILES] = $inputSmiles;
        $data[Front::EDITOR_INPUT] = $editorInput;
        $data[Front::CANVAS_INPUT_SMILE] = $inputSmile;
        $this->load->view('templates/header');
        $this->load->view('editor/index', $data);
        $this->load->view('templates/footer');
    }

}