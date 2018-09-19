<?php

use Bbdgnc\Finder\Finder;
use Bbdgnc\Finder\Enum\FindByEnum;
use Bbdgnc\Enum\Constants;

class Land extends CI_Controller {

    const REPLY_NONE = 0;
    const REPLY_OK_ONE = 1;
    const REPLY_OK_MORE = 2;

    private $data = array(Constants::CANVAS_INPUT_NAME => "", Constants::CANVAS_INPUT_SMILE => "",
        Constants::CANVAS_INPUT_FORMULA => "", Constants::CANVAS_INPUT_MASS => "", Constants::CANVAS_INPUT_IDENTIFIER => "");

    /**
     * Land constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->load->helper(array("form", "url"));
    }

    /**
     * Index - default view
     */
    public function index() {
        $this->load->view('templates/header');
        $this->load->view('pages/canvas');
        $this->load->view('pages/main', $this->data);
        $this->load->view('templates/footer');
    }

    /**
     * Form
     * Find in specific database by specific param or save data to database
     */
    public function form() {
        $this->load->library("form_validation");

        $btnFind = $this->input->post("find");
        $btnSave = $this->input->post("save");
        $btnLoad = $this->input->post("load");
        $intDatabase = $this->input->post(Constants::CANVAS_INPUT_DATABASE);
        $intFindBy = $this->input->post("search");

        if (isset($btnFind)) {
            /* Find */
            $arResult = array();
            $intResultCode = $this->findBy($intDatabase, $intFindBy, $arResult);
            switch ($intResultCode) {
                case Land::REPLY_NONE:
                    $this->index();
                    break;
                case Land::REPLY_OK_ONE:
                    $this->load->view('templates/header');
                    $this->load->view('pages/canvas');
                    $this->load->view('pages/main', $arResult);
                    $this->load->view('templates/footer');
                    break;
                case Land::REPLY_OK_MORE:
                    /* form with list view and select the right one, next find by id the right one */
                    $data['molecules'] = $arResult;
                    $this->load->view('templates/header');
                    $this->load->view('pages/select', $data);
                    $this->load->view('pages/main', $this->data);
                    $this->load->view('templates/footer');
                    break;
            }
        } else if (isset($btnSave)) {
            /* Save to database*/
        } else if (isset($btnLoad)) {
            /* Load from database */
        }
    }

    /**
     * Find by - specific param
     * @param int $intDatabase where to search
     * @param int $intFindBy find by this param
     * @param array $outMixResult output param result
     * @return int result code 0 => find none, 1 => find 1, 2 => find more than 1
     */
    private function findBy($intDatabase, $intFindBy, &$outMixResult = array()) {
        $finder = new Finder();
        /* TODO other cases */
        switch ($intFindBy) {
            case FindByEnum::IDENTIFIER:
                $this->form_validation->set_rules(Constants::CANVAS_INPUT_IDENTIFIER, "Identifier", "required");

                if ($this->form_validation->run() === false) {
                    return Land::REPLY_NONE;
                }

                $outMixResult = $finder->findByIdentifier($intDatabase, $this->input->post(Constants::CANVAS_INPUT_IDENTIFIER));
                if (isset($outMixResult)) {
                    $outMixResult = $this->transferMoleculeToFormData($outMixResult);
                    return Land::REPLY_OK_ONE;
                } else return Land::REPLY_NONE;
                break;
            case FindByEnum::NAME:
                $this->form_validation->set_rules(Constants::CANVAS_INPUT_NAME, "Name", "required");

                if ($this->form_validation->run() === false) {
                    return Land::REPLY_NONE;
                }

                $outMixResult = $finder->findByName($intDatabase, $this->input->post(Constants::CANVAS_INPUT_NAME));

                if (isset($outMixResult)) {
                    return Land::REPLY_OK_MORE;
                } else {
                    return Land::REPLY_NONE;
                }
                break;
        }

        if (sizeof($outMixResult) < 1) {
            return Land::REPLY_NONE;
        } else if (sizeof($outMixResult) == 1) {
            return Land::REPLY_OK_ONE;
        } else {
            return Land::REPLY_OK_MORE;
        }
    }

    /**
     * Transform MoleculeTO object to array for form data to set in view
     * @param \Bbdgnc\Finder\MoleculeTO $objMolecule
     * @return array data for view
     */
    private function transferMoleculeToFormData($objMolecule) {
        $arData = array();
        $arData[Constants::CANVAS_INPUT_NAME] = $objMolecule->strName;
        $arData[Constants::CANVAS_INPUT_SMILE] = $objMolecule->strSmile;
        $arData[Constants::CANVAS_INPUT_FORMULA] = $objMolecule->strFormula;
        $arData[Constants::CANVAS_INPUT_MASS] = $objMolecule->decMass;
        $arData[Constants::CANVAS_INPUT_IDENTIFIER] = $objMolecule->mixIdentifier;
        return $arData;
    }
}