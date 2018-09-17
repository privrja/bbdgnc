<?php

class Land extends CI_Controller {

    const INPUT_NAME = "name";
    const INPUT_SMILE = "smile";
    const INPUT_FORMULA = "formula";
    const INPUT_MASS = "mass";
    const INPUT_IDENTIFIER = "identifier";

    /**
     * Land constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->load->helper(array("form", "url"));
//        $this->load->library("ServerEnum");
        $this->load->library("Finder");
    }

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

    public function find() {
        $this->load->library("form_validation");

        $btnFind = $this->input->post("find");
        $intDatabase = $this->input->post("database");
        $intFindBy = $this->input->post("search");

        $intResultCode = 0;
        $arResult = array();
        if (isset($btnFind)) {
            $intResultCode = $this->findBy($intDatabase, $intFindBy, $arResult);
        }

        if ($this->form_validation->run() === false) {
            /* wrong input */
            $this->index();
        } else {
            /* ipnut OK */
            if ($intResultCode == 1) {
                $this->load->view('templates/header');
                $this->load->view('pages/main', $arResult);
                $this->load->view('templates/footer');
            } else {
                echo $intResultCode;
            }
        }

    }

    private function findBy($intDatabase, $intFindBy, &$outMixResult = array()) {
        $finder = new Finder();
        /* TODO other cases */
        switch ($intFindBy) {
            case FindByEnum::IDENTIFIER:
                $this->form_validation->set_rules(Land::INPUT_IDENTIFIER, "Identifier", "required");
                /* TODO input check should be here */
                $outMixResult = $this->transferMoleculeToFormData($finder->findByIdentifier($intDatabase, $this->input->post("identifier")));
                return 1;
        }

        if (sizeof($outMixResult) < 1) {
            return 0;
        } else if (sizeof($outMixResult) == 1) {
            return 1;
        } else {
            return 2;
        }

    }

    /**
     * Transform MoleculeTO object to array for form data to set in view
     * @param MoleculeTO $objMolecule
     * @return array
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