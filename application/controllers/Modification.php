<?php

use Bbdgnc\Base\HelperEnum;
use Bbdgnc\Base\LibraryEnum;
use Bbdgnc\Base\Logger;
use Bbdgnc\Base\ModelEnum;
use Bbdgnc\Enum\Front;
use Bbdgnc\Enum\LoggerEnum;
use Bbdgnc\TransportObjects\ModificationTO;

class Modification extends CI_Controller {

    private $errors = "";

    public function __construct() {
        parent::__construct();
        $this->load->model(ModelEnum::MODIFICATION_MODEL);
        $this->load->helper([HelperEnum::HELPER_URL, HelperEnum::HELPER_FORM]);
        $this->load->library(LibraryEnum::FORM_VALIDATION);
    }

    public function index() {
        $data['modifications'] = $this->modification_model->findAll();
        $this->load->view(Front::TEMPLATES_HEADER);
        $this->load->view('modifications/index', $data);
        $this->load->view(Front::TEMPLATES_FOOTER);
    }

    public function detail($id = 1) {
        $data['modification'] = $this->modification_model->findById($id);
        $this->load->view(Front::TEMPLATES_HEADER);
        $this->load->view('modifications/detail', $data);
        $this->load->view(Front::TEMPLATES_FOOTER);
    }

    public function new() {
        $data = [];
        $this->form_validation->set_rules(Front::MODIFICATION_NAME, 'Name', Front::REQUIRED);
        $this->form_validation->set_rules(Front::MODIFICATION_FORMULA, 'Formula', Front::REQUIRED);
        if ($this->form_validation->run() === false) {
            $data[Front::ERRORS] = $this->errors;
            $this->renderNew();
            return;
        }

        $cTerminal = $this->setupTerminal($this->input->post(Front::MODIFICATION_TERMINAL_C));
        $nTerminal = $this->setupTerminal($this->input->post(Front::MODIFICATION_TERMINAL_N));

        $modificationTO = new ModificationTO(
            $this->input->post(Front::MODIFICATION_NAME),
            $this->input->post(Front::MODIFICATION_FORMULA),
            $this->input->post(Front::MODIFICATION_MASS),
            $cTerminal, $nTerminal
        );

        try {
            $this->modification_model->insert($modificationTO);
        } catch (Exception $exception) {
            $data[Front::ERRORS] = $exception->getMessage();
            Logger::log(LoggerEnum::ERROR, $exception->getTraceAsString());
            $this->renderNew();
            return;
        }

        $this->renderNew();
    }

    private function renderNew() {
        $this->load->view(Front::TEMPLATES_HEADER);
        $this->load->view('modifications/new');
        $this->load->view(Front::TEMPLATES_FOOTER);
    }

    public function edit($id = 1) {
        $data['modification'] = $this->modification_model->findById($id);
        $this->form_validation->set_rules(Front::MODIFICATION_NAME, 'Name', Front::REQUIRED);
        $this->form_validation->set_rules(Front::MODIFICATION_FORMULA, 'Formula', Front::REQUIRED);
        if ($this->form_validation->run() === false) {
            $data[Front::ERRORS] = $this->errors;
            $this->renderEdit($data);
            return;
        }

        $cTerminal = $this->setupTerminal($this->input->post(Front::MODIFICATION_TERMINAL_C));
        $nTerminal = $this->setupTerminal($this->input->post(Front::MODIFICATION_TERMINAL_N));

        $modificationTO = new ModificationTO(
            $this->input->post(Front::MODIFICATION_NAME),
            $this->input->post(Front::MODIFICATION_FORMULA),
            $this->input->post(Front::MODIFICATION_MASS),
            $cTerminal, $nTerminal
        );

        try {
            $this->modification_model->update($id, $modificationTO);
        } catch (Exception $exception) {
            $data[Front::ERRORS] = $exception->getMessage();
            Logger::log(LoggerEnum::ERROR, $exception->getTraceAsString());
            $this->renderEdit($data);
            return;
        }
        $this->renderEdit($data);
    }

    private function renderEdit($data) {
        $this->load->view(Front::TEMPLATES_HEADER);
        $this->load->view('modifications/edit', $data);
        $this->load->view(Front::TEMPLATES_FOOTER);
    }

    private function setupTerminal($terminal) {
        return isset($terminal);
    }

}
