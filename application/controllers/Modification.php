<?php

use Bbdgnc\Base\CommonConstants;
use Bbdgnc\Base\FormulaHelper;
use Bbdgnc\Base\HelperEnum;
use Bbdgnc\Base\LibraryEnum;
use Bbdgnc\Base\Logger;
use Bbdgnc\Base\ModelEnum;
use Bbdgnc\Base\PagingEnum;
use Bbdgnc\Base\Query;
use Bbdgnc\Database\ModificationDatabase;
use Bbdgnc\Database\SequenceDatabase;
use Bbdgnc\Enum\Front;
use Bbdgnc\Enum\LoggerEnum;
use Bbdgnc\Exception\DatabaseException;
use Bbdgnc\Exception\IllegalArgumentException;
use Bbdgnc\Exception\UniqueConstraintException;
use Bbdgnc\TransportObjects\ModificationTO;

class Modification extends CI_Controller {

    private $errors = "";

    private $database;

    public function __construct() {
        parent::__construct();
        $this->load->model(ModelEnum::MODIFICATION_MODEL);
        $this->load->model(ModelEnum::SEQUENCE_MODEL);
        $this->load->helper([HelperEnum::HELPER_URL, HelperEnum::HELPER_FORM]);
        $this->load->library(LibraryEnum::FORM_VALIDATION);
        $this->load->library(LibraryEnum::PAGINATION);
        $this->database = new ModificationDatabase($this);
    }

    /**
     * Settings for filters and sorting
     * @param Query $query
     * @return string
     */
    private function setupQuery(Query $query) {
        Front::addLikeFilter(ModificationTO::NAME, ModificationTO::TABLE_NAME, $query, $this);
        Front::addLikeFilter(ModificationTO::FORMULA, ModificationTO::TABLE_NAME, $query, $this);
        Front::addLikeFilter(ModificationTO::LOSSES, ModificationTO::TABLE_NAME, $query, $this);
        Front::addSameFilter(ModificationTO::NTERMINAL, ModificationTO::TABLE_NAME, $query, $this);
        Front::addSameFilter(ModificationTO::CTERMINAL, ModificationTO::TABLE_NAME, $query, $this);
        Front::addBetweenFilter(ModificationTO::MASS, ModificationTO::TABLE_NAME, $query, $this);
        $sort = [];
        $sort[] = Front::addSortable(ModificationTO::NAME, ModificationTO::TABLE_NAME, $query, $this);
        $sort[] = Front::addSortable(ModificationTO::FORMULA, ModificationTO::TABLE_NAME, $query, $this);
        $sort[] = Front::addSortable(ModificationTO::LOSSES, ModificationTO::TABLE_NAME, $query, $this);
        $sort[] = Front::addSortable(ModificationTO::MASS, ModificationTO::TABLE_NAME, $query, $this);
        $sort[] = Front::addSortable(ModificationTO::NTERMINAL, ModificationTO::TABLE_NAME, $query, $this);
        $sort[] = Front::addSortable(ModificationTO::CTERMINAL, ModificationTO::TABLE_NAME, $query, $this);
        return Front::getSortDirection($sort);
    }


    /**
     * Page list of modifications
     * url: /modification
     * @param int $start starting page for results
     */
    public function index($start = 0) {
        $config = $data = [];
        $query = new Query();
        $data['sort'] = $this->setupQuery($query);
        $config[PagingEnum::REUSE_QUERY_STRING] = true;
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


    /**
     * Page modification detail
     * url: /modification/detail/{id}
     * @param int $id
     */
    public function detail($id = 1) {
        $data[ModificationTO::TABLE_NAME] = $this->database->findById($id);
        $this->load->view(Front::TEMPLATES_HEADER);
        $this->load->view('modifications/detail', $data);
        $this->load->view(Front::TEMPLATES_FOOTER);
    }

    /**
     * Page new modification
     * url: /modification/new
     */
    public function new() {
        $this->form_validation->set_rules(Front::MODIFICATION_NAME, 'Name', Front::REQUIRED);
        $this->form_validation->set_rules(Front::MODIFICATION_FORMULA, 'Formula', Front::REQUIRED);
        if ($this->form_validation->run() === false) {
            $data[Front::ERRORS] = $this->errors;
            $this->renderNew($data);
            return;
        }

        $cTerminal = $this->setupTerminal($this->input->post(Front::MODIFICATION_TERMINAL_C));
        $nTerminal = $this->setupTerminal($this->input->post(Front::MODIFICATION_TERMINAL_N));

        $data[Front::ERRORS] = 'Modification properly saved';
        try {
            $formula = $this->input->post(Front::MODIFICATION_FORMULA);
            $tmpMass = FormulaHelper::computeMass($formula);
            $mass = $this->input->post(Front::MODIFICATION_MASS);
            if (!isset($mass) || $mass === "") {
                $mass = $tmpMass;
            }
            $modificationTO = new ModificationTO(
                $this->input->post(Front::MODIFICATION_NAME),
                $formula, $mass, $cTerminal, $nTerminal
            );
            $this->database->insert($modificationTO);
        } catch (IllegalArgumentException $exception) {
            $data[Front::ERRORS] = $exception->getMessage();
            Logger::log(LoggerEnum::WARNING, $exception->getTraceAsString());
        } catch (UniqueConstraintException $exception) {
            $data[Front::ERRORS] = 'Modification with that name already in database!';
            Logger::log(LoggerEnum::WARNING, $exception->getTraceAsString());
        } catch (Exception $exception) {
            $data[Front::ERRORS] = $exception->getMessage();
            Logger::log(LoggerEnum::ERROR, $exception->getTraceAsString());
        } finally {
            Front::errorsCheck($data);
            $this->renderNew($data);
        }
    }

    private function renderNew($data = []) {
        $this->load->view(Front::TEMPLATES_HEADER);
        $this->load->view('modifications/new', $data);
        $this->load->view(Front::TEMPLATES_FOOTER);
    }

    /**
     * Page modification edit
     * url: /modification/edit/{id}
     * @param int $id
     */
    public function edit($id = 1) {
        $data[ModificationTO::TABLE_NAME] = $this->database->findById($id);
        $this->form_validation->set_rules(Front::MODIFICATION_NAME, 'Name', Front::REQUIRED);
        $this->form_validation->set_rules(Front::MODIFICATION_FORMULA, 'Formula', Front::REQUIRED);
        if ($this->form_validation->run() === false) {
            $data[Front::ERRORS] = $this->errors;
            $this->renderEdit($data);
            return;
        }

        $cTerminal = $this->setupTerminal($this->input->post(Front::MODIFICATION_TERMINAL_C));
        $nTerminal = $this->setupTerminal($this->input->post(Front::MODIFICATION_TERMINAL_N));

        $data[Front::ERRORS] = 'Modification properly edited';
        try {
            $formula = $this->input->post(Front::MODIFICATION_FORMULA);
            $tmpMass = FormulaHelper::computeMass($formula);
            $mass = $this->input->post(Front::MODIFICATION_MASS);
            if (!isset($mass) || $mass === "") {
                $mass = $tmpMass;
            }
            $modificationTO = new ModificationTO(
                $this->input->post(Front::MODIFICATION_NAME),
                $formula, $mass, $cTerminal, $nTerminal
            );
            $this->database->update($id, $modificationTO);
            $data[ModificationTO::TABLE_NAME] = $modificationTO->asEntity();
            $data[ModificationTO::TABLE_NAME]['id'] = $id;
        } catch (IllegalArgumentException $e) {
            $data[Front::ERRORS] = $e->getMessage();
            Logger::log(LoggerEnum::ERROR, $e->getTraceAsString());
        } catch (Exception $e) {
            $data[Front::ERRORS] = $e->getMessage();
            Logger::log(LoggerEnum::ERROR, $e->getTraceAsString());
        } finally {
            Front::errorsCheck($data);
            $this->renderEdit($data);
        }
    }

    /**
     * Page for delete modification
     * @param int $id
     */
    public function delete($id = 0) {
        $data[ModificationTO::TABLE_NAME] = $this->database->findById($id);
        try {
            $this->database->delete($id, new SequenceDatabase($this));
        } catch (DatabaseException $e) {
            $data[Front::ERRORS] = $e->getMessage();
            Logger::log(LoggerEnum::WARNING, $e->getTraceAsString());
            $this->renderEdit($data);
            return;
        } catch (Exception $e) {
            $data[Front::ERRORS] = $e->getMessage();
            Logger::log(LoggerEnum::ERROR, $e->getTraceAsString());
            Front::errorsCheck($data);
            $this->renderEdit($data);
            return;
        }
        redirect('modification');
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
