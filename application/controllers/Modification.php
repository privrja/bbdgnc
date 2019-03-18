<?php

use Bbdgnc\Base\CommonConstants;
use Bbdgnc\Base\HelperEnum;
use Bbdgnc\Base\LibraryEnum;
use Bbdgnc\Base\Logger;
use Bbdgnc\Base\ModelEnum;
use Bbdgnc\Base\PagingEnum;
use Bbdgnc\Base\Query;
use Bbdgnc\Database\ModificationDatabase;
use Bbdgnc\Enum\Front;
use Bbdgnc\Enum\LoggerEnum;
use Bbdgnc\TransportObjects\ModificationTO;

class Modification extends CI_Controller {

    private $errors = "";

    private $database;

    public function __construct() {
        parent::__construct();
        $this->load->model(ModelEnum::MODIFICATION_MODEL);
        $this->load->helper([HelperEnum::HELPER_URL, HelperEnum::HELPER_FORM]);
        $this->load->library(LibraryEnum::FORM_VALIDATION);
        $this->load->library(LibraryEnum::PAGINATION);
        $this->database = new ModificationDatabase($this);
    }

    private function setupQuery(Query $query) {
        Front::addLikeFilter('name', 'modification', $query, $this);
        Front::addLikeFilter('formula', 'modification', $query, $this);
        Front::addLikeFilter('losses', 'modification', $query, $this);
        Front::addSameFilter('nterminal','modification', $query, $this);
        Front::addSameFilter('cterminal','modification', $query, $this);
        Front::addBetweenFilter('mass',  'modification', $query, $this);
    }

    public function index($start = 0) {
        $config = [];
        $query = new Query();
        $this->setupQuery($query);
        $config[PagingEnum::BASE_URL] = base_url() . "index.php/modification";
        $config[PagingEnum::TOTAL_ROWS] = $this->database->findAllPagingCount($query);
        $config[PagingEnum::PER_PAGE] = CommonConstants::PAGING;

        $this->pagination->initialize($config);
        $data['modifications'] = $this->database->findAllPaging($start, $query);
        $data[PagingEnum::LINKS] = $this->pagination->create_links();

        $this->load->view(Front::TEMPLATES_HEADER);
        $this->load->view('modifications/index', $data);
        $this->load->view(Front::TEMPLATES_FOOTER);
    }

    public function detail($id = 1) {
        $data['modification'] = $this->database->findById($id);
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
            $this->database->insert($modificationTO);
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
        $data['modification'] = $this->database->findById($id);
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
            $this->database->update($id, $modificationTO);
        } catch (Exception $exception) {
            $data[Front::ERRORS] = $exception->getMessage();
            Logger::log(LoggerEnum::ERROR, $exception->getTraceAsString());
            $this->renderEdit($data);
            return;
        }
        $data['modification'] = $modificationTO->asEntity();
        $data['modification']['id'] = $id;
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
