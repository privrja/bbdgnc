<?php

class Land extends CI_Controller {

    const INPUT_NAME = "name";
    const INPUT_SMILE = "smile";
    const INPUT_FORMULA = "formula";
    const INPUT_MASS = "mass";
    const INPUT_IDENTIFIER = "identifier";

    const REPLY_NONE = 0;
    const REPLY_OK_ONE = 1;
    const REPLY_OK_MORE = 2;

    /**
     * Land constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->load->helper(array("form", "url"));
        $this->load->library("Finder");
    }

    /**
     * Index - default view
     */
    public function index() {
        $data = array();
        $data[Land::INPUT_NAME] = "";
        $data[Land::INPUT_SMILE] = "";
        $data[Land::INPUT_FORMULA] = "";
        $data[Land::INPUT_MASS] = "";
        $data[Land::INPUT_IDENTIFIER] = "";
        $this->load->view('templates/header');
        $this->load->view('pages/main', $data);
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
        $intDatabase = $this->input->post("database");
        $intFindBy = $this->input->post("search");

        if (isset($btnFind)) {
            /* Find */
            $intResultCode = 0;
            $arResult = array();
            $intResultCode = $this->findBy($intDatabase, $intFindBy, $arResult);
            switch ($intResultCode) {
                case Land::REPLY_NONE:
                    $this->index();
                    break;
                case Land::REPLY_OK_ONE:
                    $this->load->view('templates/header');
                    $this->load->view('pages/main', $arResult);
                    $this->load->view('templates/footer');
                    break;
                case Land::REPLY_OK_MORE:
                    /* form with list view and select the right one, next find by id the right one */
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
                $this->form_validation->set_rules(Land::INPUT_IDENTIFIER, "Identifier", "required");

                if ($this->form_validation->run() === false) {
                    return Land::REPLY_NONE;
                }

                $outMixResult = $finder->findByIdentifier($intDatabase, $this->input->post("identifier"));
                if (isset($outMixResult)) {
                    $outMixResult = $this->transferMoleculeToFormData($outMixResult);
                    return Land::REPLY_OK_ONE;
                } else return Land::REPLY_NONE;
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
     * @param MoleculeTO $objMolecule
     * @return array data for view
     */
    private function transferMoleculeToFormData($objMolecule) {
        $arData = array();
        $arData[Land::INPUT_NAME] = $objMolecule->strName;
        $arData[Land::INPUT_SMILE] = $objMolecule->strSmile;
        $arData[Land::INPUT_FORMULA] = $objMolecule->strFormula;
        $arData[Land::INPUT_MASS] = $objMolecule->decMass;
        $arData[Land::INPUT_IDENTIFIER] = $objMolecule->mixIdentifier;
        return $arData;
    }
}