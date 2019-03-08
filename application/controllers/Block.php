<?php

use Bbdgnc\Base\CommonConstants;
use Bbdgnc\Base\HelperEnum;
use Bbdgnc\Base\LibraryEnum;
use Bbdgnc\Base\Logger;
use Bbdgnc\Base\ModelEnum;
use Bbdgnc\Database\BlockDatabase;
use Bbdgnc\Enum\ComputeEnum;
use Bbdgnc\Enum\Front;
use Bbdgnc\Enum\LoggerEnum;
use Bbdgnc\Exception\UniqueConstraintException;
use Bbdgnc\TransportObjects\BlockTO;

class Block extends CI_Controller {

    private $errors = "";

    /**
     * Block constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->load->model(ModelEnum::BLOCK_MODEL);
        $this->load->helper([HelperEnum::HELPER_URL, HelperEnum::HELPER_FORM]);
        $this->load->library(LibraryEnum::FORM_VALIDATION);
        $this->load->library(LibraryEnum::PAGINATION);
    }

    public function index() {
        $data['blocks'] = $this->block_model->findAll();
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
        $blockTO = $this->setupNewBlock($smiles);
        try {
            $this->block_model->insert($blockTO);
        } catch (UniqueConstraintException $exception) {
            $data[Front::ERRORS] = "Block with this acronym already in database";
            Logger::log(LoggerEnum::WARNING, $exception->getMessage());
            $this->renderNew($data);
            return;
        } catch (Exception $exception) {
            $data[Front::ERRORS] = $exception->getMessage();
            Logger::log(LoggerEnum::ERROR, $exception->getTraceAsString());
            $this->renderNew($data);
            return;
        }
        $this->renderNew($data);
    }

    public function renderNew($data) {
        $this->load->view(Front::TEMPLATES_HEADER);
        $this->load->view('blocks/new', $data);
        $this->load->view(Front::TEMPLATES_FOOTER);
    }

    public function detail($id = 1) {
        $data['block'] = $this->block_model->findById($id);
        $this->load->view(Front::TEMPLATES_HEADER);
        $this->load->view('blocks/detail', $data);
        $this->load->view(Front::TEMPLATES_FOOTER);
    }

    public function edit($id = 1) {
        $data['block'] = $this->block_model->findById($id);
        $this->form_validation->set_rules(Front::BLOCK_NAME, 'Name', Front::REQUIRED);
        $this->form_validation->set_rules(Front::BLOCK_ACRONYM, 'Acronym', Front::REQUIRED);
        $this->form_validation->set_rules(Front::BLOCK_FORMULA, 'Formula', Front::REQUIRED);
        if ($this->form_validation->run() === false) {
            $data[Front::ERRORS] = $this->errors;
            $this->renderEditForm($data);
            return;
        }
        $blockTO = $this->setupBlock();
        try {
            $this->block_model->update($id, $blockTO);
        } catch (Exception $exception) {
            $data[Front::ERRORS] = $exception->getMessage();
            Logger::log(LoggerEnum::ERROR, $exception->getTraceAsString());
            $this->renderEditForm($data);
            return;
        }
        $this->renderEditForm($data);
    }

    private function renderEditForm($data) {
        $this->load->view(Front::TEMPLATES_HEADER);
        $this->load->view('blocks/edit', $data);
        $this->load->view(Front::TEMPLATES_FOOTER);
    }

    private function setupBlock() {
        $blockTO = new BlockTO(0,
            $this->input->post(Front::BLOCK_NAME),
            $this->input->post(Front::BLOCK_ACRONYM),
            $this->input->post(Front::BLOCK_SMILES),
            ComputeEnum::UNIQUE_SMILES);
        $blockTO->formula = $this->input->post(Front::BLOCK_FORMULA);
        $blockTO->mass = $this->input->post(Front::BLOCK_MASS);
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
            if ($mass === "") {
                $blockTO->computeMass();
            } else {
                $blockTO->mass = $mass;
            }
        } else {
            if ($formula === "") {
                $blockTO->computeFormula();
                if ($mass === "") {
                    $blockTO->computeMass();
                } else {
                    $blockTO->mass = $mass;
                }
            } else {
                $blockTO->formula = $formula;
                if ($mass === "") {
                    $blockTO->computeMass();
                } else {
                    $blockTO->mass = $mass;
                }
            }
            $blockTO->computeUniqueSmiles();
        }
        $blockTO->database = $this->input->post(Front::BLOCK_IDENTIFIER);
        $blockTO->identifier = $this->input->post(Front::BLOCK_REFERENCE_SERVER);
        return $blockTO;
    }

    public function merge($page = 0) {
        $config = array();
        $config["base_url"] = base_url() . "index.php/block/merge";
        $config["total_rows"] = $this->block_model->findGroupByFormulaCount();
        $config["per_page"] = CommonConstants::PAGING;
        $config["uri_segment"] = 3;

        $this->pagination->initialize($config);

        $blockDatabase = new BlockDatabase($this);
        $data["results"] = $blockDatabase->findMergeBlocks($page);
        $data["links"] = $this->pagination->create_links();

        $this->load->view("blocks/merge", $data);
    }

}
