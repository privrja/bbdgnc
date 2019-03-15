<?php

use Bbdgnc\Base\CommonConstants;
use Bbdgnc\Base\HelperEnum;
use Bbdgnc\Base\LibraryEnum;
use Bbdgnc\Base\Logger;
use Bbdgnc\Base\ModelEnum;
use Bbdgnc\Base\PagingEnum;
use Bbdgnc\Database\SequenceDatabase;
use Bbdgnc\Enum\Front;
use Bbdgnc\Enum\LoggerEnum;
use Bbdgnc\TransportObjects\SequenceTO;

class Sequence extends CI_Controller {

    private $errors = "";

    private $database;

    public function __construct() {
        parent::__construct();
        $this->load->model(ModelEnum::SEQUENCE_MODEL);
        $this->load->model(ModelEnum::MODIFICATION_MODEL);
        $this->load->model(ModelEnum::BLOCK_MODEL);
        $this->load->helper([HelperEnum::HELPER_URL, HelperEnum::HELPER_FORM]);
        $this->load->library(LibraryEnum::FORM_VALIDATION);
        $this->load->library(LibraryEnum::PAGINATION);
        $this->database = new SequenceDatabase($this);
    }

    public function index($start = 0) {
        $config = [];
        $config[PagingEnum::BASE_URL] = base_url() . "index.php/sequence";
        $config[PagingEnum::TOTAL_ROWS] = $this->database->findSequenceWithModificationNamesPagingCount();
        $config[PagingEnum::PER_PAGE] = CommonConstants::PAGING;

        $this->pagination->initialize($config);
        $data['sequences'] = $this->database->findSequenceWithModificationNamesPaging($start);
        $data[PagingEnum::LINKS] = $this->pagination->create_links();

        $this->load->view(Front::TEMPLATES_HEADER);
        $this->load->view('sequences/index', $data);
        $this->load->view(Front::TEMPLATES_FOOTER);
    }

    public function detail($id = 1) {
        $data = $this->database->findSequenceDetail($id);
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
            $this->database->insert($sequenceTO);
        } catch (Exception $exception) {
            $data[Front::ERRORS] = $exception->getMessage();
            Logger::log(LoggerEnum::ERROR, $exception->getTraceAsString());
            $this->renderEdit($data);
            return;
        }

        $this->renderNew($data);
    }

    public function edit($id = 1) {
        $arSequence = $this->database->findById($id);
        $data['sequence'] = $arSequence;

        $this->form_validation->set_rules(Front::SEQUENCE_TYPE, 'Type', Front::REQUIRED);
        $this->form_validation->set_rules(Front::CANVAS_INPUT_NAME, 'Name', Front::REQUIRED);
        $this->form_validation->set_rules(Front::CANVAS_INPUT_FORMULA, 'Formula', Front::REQUIRED);
        $this->form_validation->set_rules(Front::SEQUENCE, 'Sequence', Front::REQUIRED);
        if ($this->form_validation->run() === false) {
            $data[Front::ERRORS] = $this->errors;
            $this->renderEdit($data);
            return;
        }
        $sequenceTO = $this->updateSequence($arSequence);
        try {
            $this->database->update($id, $sequenceTO);
        } catch (Exception $exception) {
            $data[Front::ERRORS] = $exception->getMessage();
            Logger::log(LoggerEnum::ERROR, $exception->getTraceAsString());
            $this->renderEdit($data);
            return;
        }
        $this->renderEdit($data);
    }

    private function updateSequence(array $arSequence) {
        $sequenceTO = new SequenceTO(
            $arSequence['database'],
            $arSequence['name'],
            $arSequence['smiles'],
            $arSequence['formula'],
            $arSequence['mass'],
            $arSequence['identifier'],
            $arSequence['sequence'],
            $arSequence['type']
        );
        $sequenceTO->name = $this->input->post(Front::CANVAS_INPUT_NAME);
        $sequenceTO->database = $this->input->post(Front::CANVAS_INPUT_DATABASE);
        $sequenceTO->smiles = $this->input->post(Front::CANVAS_INPUT_SMILE);
        $sequenceTO->formula = $this->input->post(Front::CANVAS_INPUT_FORMULA);
        $sequenceTO->mass = $this->input->post(Front::CANVAS_INPUT_MASS);
        $sequenceTO->identifier = $this->input->post(Front::CANVAS_INPUT_IDENTIFIER);
        $sequenceTO->sequence = $this->input->post(Front::SEQUENCE);
        $sequenceTO->sequenceType = $this->input->post(Front::SEQUENCE_TYPE);
        return $sequenceTO;
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
