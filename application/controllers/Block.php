<?php

use Bbdgnc\Base\HelperEnum;
use Bbdgnc\Base\Logger;
use Bbdgnc\Base\ModelEnum;
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
        $this->load->library('form_validation');
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
        $formula = $this->input->post(Front::BLOCK_FORMULA);
        $smiles = $this->input->post(Front::BLOCK_SMILES);
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
        $this->load->view(Front::TEMPLATES_HEADER);
        $this->load->view('blocks/edit', $data);
        $this->load->view(Front::TEMPLATES_FOOTER);
    }

}
