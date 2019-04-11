<?php

use Bbdgnc\Base\CommonConstants;
use Bbdgnc\Base\FormulaHelper;
use Bbdgnc\Base\HelperEnum;
use Bbdgnc\Base\LibraryEnum;
use Bbdgnc\Base\Logger;
use Bbdgnc\Base\ModelEnum;
use Bbdgnc\Base\PagingEnum;
use Bbdgnc\Base\Query;
use Bbdgnc\Database\BlockDatabase;
use Bbdgnc\Enum\ComputeEnum;
use Bbdgnc\Enum\Front;
use Bbdgnc\Enum\LoggerEnum;
use Bbdgnc\Exception\IllegalArgumentException;
use Bbdgnc\Exception\UniqueConstraintException;
use Bbdgnc\Smiles\Enum\LossesEnum;
use Bbdgnc\TransportObjects\BlockTO;

class Block extends CI_Controller {

    const BLOCK_WITH_THIS_ACRONYM_ALREADY_IN_DATABASE = "Block with this acronym already in database";
    private $errors = "";

    private $database;

    /**
     * Block constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->load->model(ModelEnum::BLOCK_MODEL);
        $this->load->helper([HelperEnum::HELPER_URL, HelperEnum::HELPER_FORM]);
        $this->load->library(LibraryEnum::FORM_VALIDATION);
        $this->load->library(LibraryEnum::PAGINATION);
        $this->database = new BlockDatabase($this);
    }

    private function setupQuery(Query $query) {
        Front::addLikeFilter(BlockTO::ACRONYM, BlockTO::TABLE_NAME, $query, $this);
        Front::addLikeFilter(BlockTO::NAME, BlockTO::TABLE_NAME, $query, $this);
        Front::addLikeFilter(BlockTO::RESIDUE, BlockTO::TABLE_NAME, $query, $this);
        Front::addLikeFilter(BlockTO::LOSSES, BlockTO::TABLE_NAME, $query, $this);
        Front::addLikeFilter(BlockTO::SMILES, BlockTO::TABLE_NAME, $query, $this);
        Front::addBetweenFilter(BlockTO::MASS, BlockTO::TABLE_NAME, $query, $this);
        $sort = [];
        $sort[] = Front::addSortable(BlockTO::NAME, BlockTO::TABLE_NAME, $query, $this);
        $sort[] = Front::addSortable(BlockTO::ACRONYM, BlockTO::TABLE_NAME, $query, $this);
        $sort[] = Front::addSortable(BlockTO::RESIDUE, BlockTO::TABLE_NAME, $query, $this);
        $sort[] = Front::addSortable(BlockTO::LOSSES, BlockTO::TABLE_NAME, $query, $this);
        $sort[] = Front::addSortable(BlockTO::SMILES, BlockTO::TABLE_NAME, $query, $this);
        $sort[] = Front::addSortable(BlockTO::MASS, BlockTO::TABLE_NAME, $query, $this);
        $sort[] = Front::addSortable(BlockTO::ACRONYM, BlockTO::TABLE_NAME, $query, $this);
        return Front::getSortDirection($sort);
    }

    public function index($start = 0) {
        $config = $data = [];
        $query = new Query();
        $data['sort'] = $this->setupQuery($query);
        $config[PagingEnum::REUSE_QUERY_STRING] = true;
        $config[PagingEnum::BASE_URL] = base_url() . "index.php/block";
        $config[PagingEnum::TOTAL_ROWS] = $this->database->findAllPagingCount($query);
        $config[PagingEnum::PER_PAGE] = CommonConstants::PAGING;

        $this->pagination->initialize($config);
        $data['blocks'] = $this->database->findAllPaging($start, $query);
        $data[PagingEnum::LINKS] = $this->pagination->create_links();

        $this->load->view(Front::TEMPLATES_HEADER);
        $this->load->view('blocks/index', $data);
        $this->load->view(Front::TEMPLATES_FOOTER);
    }

    public function new() {
        $data = [];
        $this->form_validation->set_rules(Front::BLOCK_NAME, 'Name', Front::REQUIRED);
        $this->form_validation->set_rules(Front::BLOCK_ACRONYM, 'Acronym', Front::REQUIRED);
        $smiles = $this->input->post(Front::BLOCK_SMILES);
        if (!isset($smiles) || $smiles === "") {
            $this->form_validation->set_rules(Front::BLOCK_FORMULA, 'Formula', Front::REQUIRED);
        }
        if ($this->form_validation->run() === false) {
            $data[Front::ERRORS] = $this->errors;
            $this->renderNew($data);
            return;
        }
        $data[Front::ERRORS] = 'Block correctly saved';
        try {
            $blockTO = $this->setupNewBlock($smiles);
            $this->database->insert($blockTO);
        } catch (IllegalArgumentException $exception) {
            $data[Front::ERRORS] = $exception->getMessage();
            Logger::log(LoggerEnum::WARNING, $exception->getTraceAsString());
        } catch (UniqueConstraintException $exception) {
            $data[Front::ERRORS] = self::BLOCK_WITH_THIS_ACRONYM_ALREADY_IN_DATABASE;
            Logger::log(LoggerEnum::WARNING, $exception->getMessage());
        } catch (Exception $exception) {
            $data[Front::ERRORS] = $exception->getMessage();
            Logger::log(LoggerEnum::ERROR, $exception->getTraceAsString());
        } finally {
            Front::errorsCheck($data);
            $this->renderNew($data);
        }
    }

    public function renderNew($data) {
        $this->load->view(Front::TEMPLATES_HEADER);
        $this->load->view('blocks/new', $data);
        $this->load->view(Front::TEMPLATES_FOOTER);
    }

    public function detail($id = 1) {
        $data[BlockTO::TABLE_NAME] = $this->database->findById($id);
        $this->load->view(Front::TEMPLATES_HEADER);
        $this->load->view('blocks/detail', $data);
        $this->load->view(Front::TEMPLATES_FOOTER);
    }

    public function edit($id = 1) {
        $data[BlockTO::TABLE_NAME] = $this->database->findById($id);
        $this->form_validation->set_rules(Front::BLOCK_NAME, 'Name', Front::REQUIRED);
        $this->form_validation->set_rules(Front::BLOCK_ACRONYM, 'Acronym', Front::REQUIRED);
        $smiles = $this->input->post(Front::BLOCK_SMILES);
        if (!isset($smiles) || $smiles === "") {
            $this->form_validation->set_rules(Front::BLOCK_FORMULA, 'Formula', Front::REQUIRED);
        }
        if ($this->form_validation->run() === false) {
            $data[Front::ERRORS] = $this->errors;
            $this->renderEditForm($data);
            return;
        }

        $data[Front::ERRORS] = 'Block properly edited';
        try {
            $blockTO = $this->setupBlock();
            $this->database->update($id, $blockTO);
        } catch (UniqueConstraintException $exception) {
            $data[Front::ERRORS] = self::BLOCK_WITH_THIS_ACRONYM_ALREADY_IN_DATABASE;
            Logger::log(LoggerEnum::WARNING, $exception->getTraceAsString());
        } catch (IllegalArgumentException $exception) {
            $data[Front::ERRORS] = $exception->getMessage();
            Logger::log(LoggerEnum::WARNING, $exception->getTraceAsString());
        } catch (Exception $exception) {
            $data[Front::ERRORS] = $exception->getMessage();
            Logger::log(LoggerEnum::ERROR, $exception->getTraceAsString());
        } finally {
            Front::errorsCheck($data);
            $this->renderEditForm($data);
        }
    }

    private function renderEditForm($data) {
        $this->load->view(Front::TEMPLATES_HEADER);
        $this->load->view('blocks/edit', $data);
        $this->load->view(Front::TEMPLATES_FOOTER);
    }

    private function setupBlock() {
        $formula = $this->input->post(Front::BLOCK_FORMULA);
        $mass = $this->input->post(Front::BLOCK_MASS);
        $smiles = $this->input->post(Front::BLOCK_SMILES);
        $blockTO = new BlockTO(0,
            $this->input->post(Front::BLOCK_NAME),
            $this->input->post(Front::BLOCK_ACRONYM),
            $smiles,
            ComputeEnum::UNIQUE_SMILES);
        if ($formula === "") {
            $blockTO->formula = FormulaHelper::formulaFromSmiles($smiles, LossesEnum::H2O);
            FormulaHelper::computeMassIfMassNotSet($mass, $blockTO->formula, $blockTO);
        } else {
            $blockTO->formula = $formula;
            FormulaHelper::computeMassIfMassNotSet($mass, $blockTO->formula, $blockTO);
        }
        $blockTO->losses = $this->input->post(Front::BLOCK_NEUTRAL_LOSSES);
        $blockTO->database = $this->input->post(Front::BLOCK_REFERENCE_SERVER);
        $blockTO->identifier = $this->input->post(Front::BLOCK_IDENTIFIER);
        return $blockTO;
    }

    private function setupNewBlock($smiles) {
        $formula = $this->input->post(Front::BLOCK_FORMULA);
        $mass = $this->input->post(Front::BLOCK_MASS);
        $blockTO = new BlockTO(0, $this->input->post(Front::BLOCK_NAME),
            $this->input->post(Front::BLOCK_ACRONYM),
            $smiles, ComputeEnum::NO);

        if ($smiles === "") {
            $blockTO->formula = $formula;
            $this->setupMass($blockTO, $mass);
        } else {
            if ($formula === "") {
                $blockTO->computeFormula();
                $this->setupMass($blockTO, $mass);
            } else {
                $blockTO->formula = $formula;
                $this->setupMass($blockTO, $mass);
            }
            $blockTO->computeUniqueSmiles();
        }
        $blockTO->losses = $this->input->post(Front::BLOCK_NEUTRAL_LOSSES);
        $blockTO->database = $this->input->post(Front::BLOCK_REFERENCE_SERVER);
        $blockTO->identifier = $this->input->post(Front::BLOCK_REFERENCE);
        return $blockTO;
    }

    private function setupMass($blockTO, $mass) {
        $tmpMass = FormulaHelper::computeMass($blockTO->formula);
        if ($mass === "") {
            $blockTO->mass = $tmpMass;
        } else {
            $blockTO->mass = $mass;
        }
    }

    public function merge($page = 0) {
        $config = [];
        $config[PagingEnum::BASE_URL] = base_url() . "index.php/block/merge";
        $config[PagingEnum::TOTAL_ROWS] = $this->database->findGroupByFormulaCount();
        $config[PagingEnum::PER_PAGE] = CommonConstants::PAGING;

        $this->pagination->initialize($config);

        $data['results'] = $this->database->findMergeBlocks($page);
        $data[PagingEnum::LINKS] = $this->pagination->create_links();

        $this->load->view(Front::TEMPLATES_HEADER);
        $this->load->view("blocks/merge", $data);
        $this->load->view(Front::TEMPLATES_FOOTER);
    }

}
