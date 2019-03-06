<?php

use Bbdgnc\Base\HelperEnum;
use Bbdgnc\Base\LibraryEnum;
use Bbdgnc\Base\Logger;
use Bbdgnc\Base\ModelEnum;
use Bbdgnc\Enum\Front;
use Bbdgnc\Enum\LoggerEnum;
use Bbdgnc\TransportObjects\SequenceTO;

class Sequence extends CI_Controller {

    private $errors = "";

    private $sequenceDatabase;

    public function __construct() {
        parent::__construct();
        $this->load->model(ModelEnum::SEQUENCE_MODEL);
        $this->load->model(ModelEnum::MODIFICATION_MODEL);
        $this->load->model(ModelEnum::BLOCK_MODEL);
        $this->load->helper([HelperEnum::HELPER_URL, HelperEnum::HELPER_FORM]);
        $this->load->library(LibraryEnum::FORM_VALIDATION);
        $this->sequenceDatabase = new SequenceDatabase($this);
    }

    public function index() {
        $data['sequences'] = $this->sequence_model->findAll();
        $this->load->view(Front::TEMPLATES_HEADER);
        $this->load->view('sequences/index', $data);
        $this->load->view(Front::TEMPLATES_FOOTER);
    }

    public function detail($id = 1) {
        $data = $this->sequenceDatabase->findSequenceDetail($id);
        $this->load->view(Front::TEMPLATES_HEADER);
        $this->load->view('sequences/detail', $data);
        $this->load->view(Front::TEMPLATES_FOOTER);
    }

    public function new() {
        $data = [];
        $this->form_validation->set_rules(Front::SEQUENCE_TYPE, 'Type', Front::REQUIRED);
        $this->form_validation->set_rules(Front::CANVAS_INPUT_NAME, 'Name', Front::REQUIRED);
        $this->form_validation->set_rules(Front::CANVAS_INPUT_FORMULA, 'Formula', Front::REQUIRED);
        $this->form_validation->set_rules(Front::SEQUENCE, 'Sequence', Front::REQUIRED);
        if ($this->form_validation->run() === false) {
            $data[Front::ERRORS] = $this->errors;
            $this->renderNew($data);
            return;
        }
        $sequenceTO = $this->createSequence();
        try {
            $this->sequence_model->insert($sequenceTO);
        } catch (Exception $exception) {
            $data[Front::ERRORS] = $exception->getMessage();
            Logger::log(LoggerEnum::ERROR, $exception->getTraceAsString());
            $this->renderEdit($data);
            return;
        }

        $this->renderNew($data);
    }

    public function edit($id = 1) {
        $data['sequence'] = $this->sequence_model->findById($id);

        $this->form_validation->set_rules(Front::SEQUENCE_TYPE, 'Type', Front::REQUIRED);
        $this->form_validation->set_rules(Front::CANVAS_INPUT_NAME, 'Name', Front::REQUIRED);
        $this->form_validation->set_rules(Front::CANVAS_INPUT_FORMULA, 'Formula', Front::REQUIRED);
        $this->form_validation->set_rules(Front::SEQUENCE, 'Sequence', Front::REQUIRED);
        if ($this->form_validation->run() === false) {
            $data[Front::ERRORS] = $this->errors;
            $this->renderEdit($data);
            return;
        }
        $sequenceTO = $this->createSequence();
        try {
            $this->sequence_model->update($id, $sequenceTO);
        } catch (Exception $exception) {
            $data[Front::ERRORS] = $exception->getMessage();
            Logger::log(LoggerEnum::ERROR, $exception->getTraceAsString());
            $this->renderEdit($data);
            return;
        }
        $this->renderEdit($data);
    }

    private function createSequence() {
        return new SequenceTO(
            $this->input->post(Front::CANVAS_INPUT_DATABASE),
            $this->input->post(Front::CANVAS_INPUT_NAME),
            $this->input->post(Front::CANVAS_INPUT_SMILE),
            $this->input->post(Front::CANVAS_INPUT_FORMULA),
            $this->input->post(Front::CANVAS_INPUT_MASS),
            $this->input->post(Front::CANVAS_INPUT_IDENTIFIER),
            $this->input->post(Front::SEQUENCE),
            $this->input->post(Front::SEQUENCE_TYPE)
        );
    }

    private function renderEdit($data) {
        $this->load->view(Front::TEMPLATES_HEADER);
        $this->load->view('sequences/edit', $data);
        $this->load->view(Front::TEMPLATES_FOOTER);
    }

    private function renderNew($data) {
        $this->load->view(Front::TEMPLATES_HEADER);
        $this->load->view('sequences/new', $data);
        $this->load->view(Front::TEMPLATES_FOOTER);
    }

}
