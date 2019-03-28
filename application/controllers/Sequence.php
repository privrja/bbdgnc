<?php

use Bbdgnc\Base\CommonConstants;
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
use Bbdgnc\Enum\ModificationHelperTypeEnum;
use Bbdgnc\TransportObjects\SequenceTO;

class Sequence extends CI_Controller {

    const SEQUENCE_ID = 'sequenceId';
    const MODIFICATION_ID = '_modification_id';
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

    private function setupQuery(Query $query) {
        Front::addSameFilter(SequenceTO::TYPE, SequenceTO::TABLE_NAME, $query, $this);
        Front::addLikeFilter(SequenceTO::NAME, SequenceTO::TABLE_NAME, $query, $this);
        Front::addLikeFilter(SequenceTO::FORMULA, SequenceTO::TABLE_NAME, $query, $this);
        Front::addBetweenFilter(SequenceTO::MASS, SequenceTO::TABLE_NAME, $query, $this);
        Front::addLikeFilter(SequenceTO::SEQUENCE, SequenceTO::TABLE_NAME, $query, $this);
        $sort = [];
        $sort[] = Front::addSortable(SequenceTO::TYPE, SequenceTO::TABLE_NAME, $query, $this);
        $sort[] = Front::addSortable(SequenceTO::NAME, SequenceTO::TABLE_NAME, $query, $this);
        $sort[] = Front::addSortable(SequenceTO::FORMULA, SequenceTO::TABLE_NAME, $query, $this);
        $sort[] = Front::addSortable(SequenceTO::MASS, SequenceTO::TABLE_NAME, $query, $this);
        $sort[] = Front::addSortable(SequenceTO::SEQUENCE, SequenceTO::TABLE_NAME, $query, $this);
        return Front::getSortDirection($sort);
    }

    public function index($start = 0) {
        $config = $data = [];
        $query = new Query();
        $data['sort'] = $this->setupQuery($query);
        $config[PagingEnum::REUSE_QUERY_STRING] = true;
        $config[PagingEnum::BASE_URL] = base_url() . "index.php/sequence";
        $config[PagingEnum::TOTAL_ROWS] = $this->database->findSequenceWithModificationNamesPagingCount($query);
        $config[PagingEnum::PER_PAGE] = CommonConstants::PAGING;

        $this->pagination->initialize($config);
        $data['sequences'] = $this->database->findSequenceWithModificationNamesPaging($start, $query);
        $data[PagingEnum::LINKS] = $this->pagination->create_links();

        $this->load->view(Front::TEMPLATES_HEADER);
        $this->load->view('sequences/index', $data);
        $this->load->view(Front::TEMPLATES_FOOTER);
    }

    public function detail($id = 1) {
        $modificationDatabase = new ModificationDatabase($this);
        $data = $this->database->findSequenceDetail($id);
        $data['modifications'] = $modificationDatabase->findAllSelect();
        $this->load->view(Front::TEMPLATES_HEADER);
        $this->load->view(Front::PAGES_CANVAS);
        $this->load->view('sequences/sequence', $data);
        $this->load->view('sequences/blocks', $data);
        $this->load->view(Front::TEMPLATES_FOOTER);
    }

    public function new() {
        $data = [];
        $this->form_validation->set_rules(Front::SEQUENCE_TYPE, 'Type', Front::REQUIRED);
        $this->form_validation->set_rules(Front::CANVAS_INPUT_NAME, 'Name', Front::REQUIRED);
        $this->form_validation->set_rules(Front::CANVAS_INPUT_FORMULA, 'Formula', Front::REQUIRED);
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
        $modificationDatabase = new ModificationDatabase($this);
        $data = $this->database->findSequenceDetail($id);
        $data['modifications'] = $modificationDatabase->findAllSelect();

        $this->form_validation->set_rules(Front::SEQUENCE_TYPE, 'Type', Front::REQUIRED);
        $this->form_validation->set_rules(Front::CANVAS_INPUT_NAME, 'Name', Front::REQUIRED);
        $this->form_validation->set_rules(Front::CANVAS_INPUT_FORMULA, 'Formula', Front::REQUIRED);
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

    public function modifications() {
        $modificationDatabase = new ModificationDatabase($this);
        // TODO validate sequenceId and modification id
        $id = $this->input->post(self::SEQUENCE_ID);
        $data[SequenceTO::TABLE_NAME] = $this->database->findById($id);

        $modificationDatabase->startTransaction();
        $branchChar = ModificationHelperTypeEnum::startModification($data[SequenceTO::TABLE_NAME][SequenceTO::TYPE]);
        for ($index = 0; $index < 3; ++$index) {
            $this->saveModification($branchChar, $id);
            $branchChar = ModificationHelperTypeEnum::changeBranchChar($branchChar, $data[SequenceTO::TABLE_NAME][SequenceTO::TYPE]);
            if (ModificationHelperTypeEnum::isEnd($branchChar)) {
                break;
            }
        }
        $modificationDatabase->endTransaction();
        $data = $this->database->findSequenceDetail($id);
        $data[Front::ERRORS] = $this->errors;
        $data['modifications'] = $modificationDatabase->findAllSelect();
        $this->renderEdit($data);
    }

    private function saveModification(string $terminalValue, $sequenceId) {
        $this->database->updateModification($sequenceId, $this->input->post($terminalValue . Front::MODIFICATION_SELECT), $terminalValue . self::MODIFICATION_ID);
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
        $sequenceTO->decays = $arSequence[SequenceTO::DECAYS];
        $sequenceTO->nModification = $arSequence[SequenceTO::N_MODIFICATION_ID];
        $sequenceTO->cModification = $arSequence[SequenceTO::C_MODIFICATION_ID];
        $sequenceTO->bModification = $arSequence[SequenceTO::B_MODIFICATION_ID];
        $sequenceTO->name = $this->input->post(Front::CANVAS_INPUT_NAME);
        $sequenceTO->database = $this->input->post(Front::CANVAS_INPUT_DATABASE);
        $sequenceTO->smiles = $this->input->post(Front::CANVAS_INPUT_SMILE);
        $sequenceTO->formula = $this->input->post(Front::CANVAS_INPUT_FORMULA);
        $sequenceTO->mass = $this->input->post(Front::CANVAS_INPUT_MASS);
        $sequenceTO->identifier = $this->input->post(Front::CANVAS_INPUT_IDENTIFIER);
        $sequenceTO->sequence = $this->input->post(Front::SEQUENCE);
        $sequenceTO->sequenceType = $this->input->post(Front::SEQUENCE_TYPE);
        $sequenceTO->decays = $this->input->post(Front::DECAYS);
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
