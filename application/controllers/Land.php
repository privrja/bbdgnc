<?php

use Bbdgnc\Finder\Finder;
use Bbdgnc\Finder\Enum\FindByEnum;
use Bbdgnc\Enum\Front;
use Bbdgnc\Finder\Enum\ResultEnum;
use Bbdgnc\Finder\Enum\ServerEnum;
use Bbdgnc\Finder\PubChemFinder;

class Land extends CI_Controller {

    /** @var string cookie identifier for next results */
    const COOKIE_NEXT_RESULTS = 'find-next-results';

    /** @var int expire time of cookie 1 hour */
    const COOKIE_EXPIRE_HOUR = 3600;

    /**
     * Get Default data for view
     * @return array
     */
    private function getData() {
        return array(
            Front::CANVAS_INPUT_NAME => "", Front::CANVAS_INPUT_SMILE => "",
            Front::CANVAS_INPUT_FORMULA => "", Front::CANVAS_INPUT_MASS => "",
            Front::CANVAS_INPUT_DEFLECTION => "", Front::CANVAS_INPUT_IDENTIFIER => "",
            Front::CANVAS_HIDDEN_DATABASE => ""
        );
    }

    /**
     * Land constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->load->helper(array("form", "url"));
    }

    /**
     * Index - default view
     * @param array $viewData default null, data for view to print
     */
    public function index($viewData = null) {
        $this->load->view('templates/header');
        $this->load->view('pages/canvas');
        if (isset($viewData)) {
            $this->load->view('pages/main', $viewData);
        } else {
            $this->load->view('pages/main', $this->getData());
        }
        $this->load->view('templates/footer');
    }

    private function renderSelect($viewSelectData, $viewData) {
        $this->load->view('templates/header');
        $this->load->view('pages/select', $viewSelectData);
        $this->load->view('pages/main', $viewData);
        $this->load->view('templates/footer');
    }

    /**
     * Form
     * Find in specific database by specific param or save data to database
     */
    public function form() {
        /* load form validation library */
        $this->load->library("form_validation");

        /* get important input data */
        $btnFind = $this->input->post("find");
        $btnSave = $this->input->post("save");
        $btnLoad = $this->input->post("load");
        $intDatabase = $this->input->post(Front::CANVAS_INPUT_DATABASE);
        $intFindBy = $this->input->post(Front::CANVAS_INPUT_SEARCH_BY);
        $blMatch = $this->input->post(Front::CANVAS_INPUT_MATCH);

        if (isset($btnFind)) {
            /* Find */
            $this->find($intDatabase, $intFindBy, $blMatch);
        } else if (isset($btnSave)) {
            /* Save to database */
        } else if (isset($btnLoad)) {
            /* Load from database */
        }
    }

    /**
     * Find data on selected server
     * @param int $intDatabase selected server
     * @param int $intFindBy find by seleceted input
     * @param boolean $blMatch if exact match selected
     */
    private function find($intDatabase, $intFindBy, $blMatch) {
        /* input check */
        $this->form_validation->set_rules(Front::CANVAS_INPUT_DATABASE, "Database", "required");
        $this->form_validation->set_rules(Front::CANVAS_INPUT_SEARCH_BY, "Search by", "required");
        if ($this->form_validation->run() === false) {
            $this->index($this->getLastData());
            return;
        }

        /* search options */
        $arSearchOptions = array();
        if (isset($blMatch)) {
            $arSearchOptions[Finder::OPTION_EXACT_MATCH] = true;
        } else {
            $arSearchOptions[Finder::OPTION_EXACT_MATCH] = false;
        }

        $intResultCode = $this->findBy($intDatabase, $intFindBy, $outArResult, $outArNextResult, $arSearchOptions);
        switch ($intResultCode) {
            case ResultEnum::REPLY_NONE:
                $this->input->set_cookie(self::COOKIE_NEXT_RESULTS, "", 0);
                $this->index($this->getLastData());
                break;
            case ResultEnum::REPLY_OK_ONE:
                $this->input->set_cookie(self::COOKIE_NEXT_RESULTS, "", 0);
                if (empty($outArResult[Front::CANVAS_INPUT_NAME])) {
                    $outArResult[Front::CANVAS_INPUT_NAME] = $this->input->post(Front::CANVAS_INPUT_NAME);
                }
                $outArResult[Front::CANVAS_INPUT_DEFLECTION] = "";
                $this->index($outArResult);
                break;
            case ResultEnum::REPLY_OK_MORE:
                /* form with list view and select the right one, next find by id the right one */
                $data = $this->getLastDataToHidden();
                $data['molecules'] = $outArResult;
                if (!empty($outArNextResult)) {
                    // TODO get only first 160 ids, max value to cookie can be overhead, maybe better store to database
                    array_splice($outArNextResult, 160);
                    // save next results to cookie
                    $this->input->set_cookie(self::COOKIE_NEXT_RESULTS, serialize($outArNextResult), self::COOKIE_EXPIRE_HOUR);
                } else {
                    $this->input->set_cookie(self::COOKIE_NEXT_RESULTS, "", 0);
                }
                $this->renderSelect($data, $this->getLastData());
                break;
        }
    }

    /**
     * Render default view with canvas and form. Select data from list and set them to form
     */
    public function select() {
        $data = $this->getLastData();
        $data[Front::CANVAS_HIDDEN_DATABASE] = $this->input->post(Front::CANVAS_HIDDEN_DATABASE);

        /* Problem with name */
        $strName = $this->input->post(Front::CANVAS_INPUT_NAME);
        if ($this->input->post(Front::CANVAS_HIDDEN_DATABASE) == ServerEnum::PUBCHEM) {
            $pubChemFinder = new PubChemFinder();
            $strIupacName = $strName;
            $intResultCode = $pubChemFinder->findName($this->input->post(Front::CANVAS_INPUT_IDENTIFIER), $strName);
            if ($intResultCode == ResultEnum::REPLY_NONE) {
                $data[Front::CANVAS_INPUT_NAME] = $strIupacName;
            } else {
                $data[Front::CANVAS_INPUT_NAME] = $strName;
            }
        } else {
            if (!empty($strName)) {
                $data[Front::CANVAS_INPUT_NAME] = $strName;
            } else {
                /* TODO maybe can be deleted with the hidden input */
                $data[Front::CANVAS_INPUT_NAME] = $this->input->post(Front::CANVAS_HIDDEN_NAME);
            }
        }
        $this->index($data);
    }

    /**
     * Render view for next values from finding
     */
    public function next() {
        $arNext = unserialize($this->input->cookie(self::COOKIE_NEXT_RESULTS));
        $intDatabase = $this->input->post(Front::CANVAS_HIDDEN_DATABASE);
        $data = $this->getLastHiddenData();

        if (isset($arNext) && !empty($arNext)) {
            $arResult = array_splice($arNext, 0, \Bbdgnc\Finder\IFinder::FIRST_X_RESULTS);

            $finder = new Finder();
            $finder->findByIdentifiers($intDatabase, $arResult, $outArResult);

            $data['molecules'] = $outArResult;
            if (!empty($arNext)) {
                // save next results to cookie
                $this->input->set_cookie(self::COOKIE_NEXT_RESULTS, serialize($arNext), self::COOKIE_EXPIRE_HOUR);
            }
            $this->renderSelect($data, $this->getLastHiddenDataToInput());
        } else {
            $this->index($this->getLastData());
        }
    }

    /**
     * Get last data from hidden input and set it to array for view hidden
     * @return array data for view
     */
    private function getLastHiddenData() {
        $arViewData = array();
        $arViewData[Front::CANVAS_HIDDEN_DATABASE] = $this->input->post(Front::CANVAS_HIDDEN_DATABASE);
        $arViewData[Front::CANVAS_HIDDEN_NAME] = $this->input->post(Front::CANVAS_HIDDEN_NAME);
        $arViewData[Front::CANVAS_HIDDEN_SMILE] = $this->input->post(Front::CANVAS_HIDDEN_SMILE);
        $arViewData[Front::CANVAS_HIDDEN_FORMULA] = $this->input->post(Front::CANVAS_HIDDEN_FORMULA);
        $arViewData[Front::CANVAS_HIDDEN_MASS] = $this->input->post(Front::CANVAS_HIDDEN_MASS);
        $arViewData[Front::CANVAS_HIDDEN_DEFLECTION] = $this->input->post(Front::CANVAS_HIDDEN_DEFLECTION);
        $arViewData[Front::CANVAS_HIDDEN_IDENTIFIER] = $this->input->post(Front::CANVAS_HIDDEN_IDENTIFIER);
        return $arViewData;
    }

    /**
     * Get last data from hidden input and set it to array for view
     * @return array data for view
     */
    private function getLastHiddenDataToInput() {
        $arViewData = array();
        $arViewData[Front::CANVAS_HIDDEN_DATABASE] = $this->input->post(Front::CANVAS_HIDDEN_DATABASE);
        $arViewData[Front::CANVAS_INPUT_NAME] = $this->input->post(Front::CANVAS_HIDDEN_NAME);
        $arViewData[Front::CANVAS_INPUT_SMILE] = $this->input->post(Front::CANVAS_HIDDEN_SMILE);
        $arViewData[Front::CANVAS_INPUT_FORMULA] = $this->input->post(Front::CANVAS_HIDDEN_FORMULA);
        $arViewData[Front::CANVAS_INPUT_MASS] = $this->input->post(Front::CANVAS_HIDDEN_MASS);
        $arViewData[Front::CANVAS_INPUT_DEFLECTION] = $this->input->post(Front::CANVAS_HIDDEN_DEFLECTION);
        $arViewData[Front::CANVAS_INPUT_IDENTIFIER] = $this->input->post(Front::CANVAS_HIDDEN_IDENTIFIER);
        return $arViewData;
    }

    /**
     * Get last data from input and set it to array for view hidden
     * @return array data for view
     */
    private function getLastDataToHidden() {
        $arViewData = array();
        $arViewData[Front::CANVAS_HIDDEN_DATABASE] = $this->input->post(Front::CANVAS_INPUT_DATABASE);
        $arViewData[Front::CANVAS_HIDDEN_NAME] = $this->input->post(Front::CANVAS_INPUT_NAME);
        $arViewData[Front::CANVAS_HIDDEN_SMILE] = $this->input->post(Front::CANVAS_INPUT_SMILE);
        $arViewData[Front::CANVAS_HIDDEN_FORMULA] = $this->input->post(Front::CANVAS_INPUT_FORMULA);
        $arViewData[Front::CANVAS_HIDDEN_MASS] = $this->input->post(Front::CANVAS_INPUT_MASS);
        $arViewData[Front::CANVAS_HIDDEN_DEFLECTION] = $this->input->post(Front::CANVAS_INPUT_DEFLECTION);
        $arViewData[Front::CANVAS_HIDDEN_IDENTIFIER] = $this->input->post(Front::CANVAS_INPUT_IDENTIFIER);
        return $arViewData;
    }

    /**
     * Get last data from input and set it to array for view
     * @return array data for view
     */
    private function getLastData() {
        $arViewData = array();
        $arViewData[Front::CANVAS_HIDDEN_DATABASE] = $this->input->post(Front::CANVAS_INPUT_DATABASE);
        $arViewData[Front::CANVAS_INPUT_NAME] = $this->input->post(Front::CANVAS_INPUT_NAME);
        $arViewData[Front::CANVAS_INPUT_SMILE] = $this->input->post(Front::CANVAS_INPUT_SMILE);
        $arViewData[Front::CANVAS_INPUT_FORMULA] = $this->input->post(Front::CANVAS_INPUT_FORMULA);
        $arViewData[Front::CANVAS_INPUT_MASS] = $this->input->post(Front::CANVAS_INPUT_MASS);
        $arViewData[Front::CANVAS_INPUT_DEFLECTION] = $this->input->post(Front::CANVAS_INPUT_DEFLECTION);
        $arViewData[Front::CANVAS_INPUT_IDENTIFIER] = $this->input->post(Front::CANVAS_INPUT_IDENTIFIER);
        return $arViewData;
    }

    /**
     * Find by - specific param
     * @param int $intDatabase where to search
     * @param int $intFindBy find by this param
     * @param array $outArResult output param result only first X results, can be influenced by param IFinder::FIRST_X_RESULTS
     * @param array $outArNextResult next results identifiers
     * @return int result code 0 => find none, 1 => find 1, 2 => find more than 1
     */
    private function findBy($intDatabase, $intFindBy, &$outArResult = array(), &$outArNextResult = array(), $arSearchOptions = array()) {
        $finder = new Finder();
        $finder->setOptions($arSearchOptions);
        switch ($intFindBy) {
            case FindByEnum::IDENTIFIER:
                $this->form_validation->set_rules(Front::CANVAS_INPUT_IDENTIFIER, "Identifier", "required");
                if ($this->form_validation->run() === false) {
                    return ResultEnum::REPLY_NONE;
                }
                return $finder->findByIdentifier($intDatabase, $this->input->post(Front::CANVAS_INPUT_IDENTIFIER), $outArResult);
            case FindByEnum::NAME:
                $this->form_validation->set_rules(Front::CANVAS_INPUT_NAME, "Name", "required");
                if ($this->form_validation->run() === false) {
                    return ResultEnum::REPLY_NONE;
                }
                return $finder->findByName($intDatabase, $this->input->post(Front::CANVAS_INPUT_NAME), $outArResult, $outArNextResult);
            case FindByEnum::FORMULA:
                $this->form_validation->set_rules(Front::CANVAS_INPUT_FORMULA, "Formula", "required");
                if ($this->form_validation->run() === false) {
                    return ResultEnum::REPLY_NONE;
                }
                return $finder->findByFormula($intDatabase, $this->input->post(Front::CANVAS_INPUT_FORMULA), $outArResult, $outArNextResult);
            case FindByEnum::SMILE:
                $this->form_validation->set_rules(Front::CANVAS_INPUT_SMILE, "SMILES", "required");
                if ($this->form_validation->run() === false) {
                    return ResultEnum::REPLY_NONE;
                }
                return $finder->findBySmile($intDatabase, $this->input->post(Front::CANVAS_INPUT_SMILE), $outArResult, $outArNextResult);
            case FindByEnum::MASS:
                return ResultEnum::REPLY_NONE;
        }
    }

}
