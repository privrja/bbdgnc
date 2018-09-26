<?php

use Bbdgnc\Finder\Finder;
use Bbdgnc\Finder\Enum\FindByEnum;
use Bbdgnc\Enum\Front;
use Bbdgnc\Finder\Enum\ResultEnum;
use Bbdgnc\Finder\Enum\ServerEnum;
use Bbdgnc\Finder\PubChemFinder;

class Land extends CI_Controller {

    const COOKIE_NEXT_RESULTS = 'find-next-results';

    /** @var int expire time of cookie 1 hour */
    const COOKIE_EXPIRE_HOUR = 3600;

    private function getData() {
        return array(
            Front::CANVAS_INPUT_NAME => "", Front::CANVAS_INPUT_SMILE => "",
            Front::CANVAS_INPUT_FORMULA => "", Front::CANVAS_INPUT_MASS => "",
            Front::CANVAS_INPUT_IDENTIFIER => "", Front::CANVAS_HIDDEN_DATABASE => ""
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
     */
    public function index() {
        $this->load->view('templates/header');
        $this->load->view('pages/canvas');
        $this->load->view('pages/main', $this->getData());
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
        $intDatabase = $this->input->post(Front::CANVAS_INPUT_DATABASE);
        $intFindBy = $this->input->post("search");
        $arSearchOptions = array();
        $blMatch = $this->input->post(Front::CANVAS_INPUT_MATCH);
        if (isset($blMatch)) {
            $arSearchOptions[Finder::OPTION_EXACT_MATCH] = true;
        } else {
            $arSearchOptions[Finder::OPTION_EXACT_MATCH] = false;
        }

        if (isset($btnFind)) {
            /* Find */
            $outArResult = array();
            $intResultCode = $this->findBy($intDatabase, $intFindBy, $outArResult, $outArNextResult, $arSearchOptions);
            switch ($intResultCode) {
                case ResultEnum::REPLY_NONE:
                    $this->input->set_cookie(self::COOKIE_NEXT_RESULTS, "", 0);
                    $this->index();
                    break;
                case ResultEnum::REPLY_OK_ONE:
                    $this->input->set_cookie(self::COOKIE_NEXT_RESULTS, "", 0);
                    $this->load->view('templates/header');
                    $this->load->view('pages/canvas');
                    if (empty($outArResult[Front::CANVAS_INPUT_NAME])) {
                        $outArResult[Front::CANVAS_INPUT_NAME] = $this->input->post(Front::CANVAS_INPUT_NAME);
                    }
                    $this->load->view('pages/main', $outArResult);
                    $this->load->view('templates/footer');
                    break;
                case ResultEnum::REPLY_OK_MORE:
                    /* form with list view and select the right one, next find by id the right one */
                    $data['molecules'] = $outArResult;
                    $rightData = $this->getData();
                    $data[Front::CANVAS_HIDDEN_NAME] = $this->input->post(Front::CANVAS_INPUT_NAME);
                    if (!empty($outArNextResult)) {
                        // get only first 160 ids, max value to cookie can be overhead, maybe better store to database
                        array_splice($outArNextResult, 160);
                        // save next results to cookie
                        $this->input->set_cookie(self::COOKIE_NEXT_RESULTS, serialize($outArNextResult), self::COOKIE_EXPIRE_HOUR);
                    } else {
                        $this->input->set_cookie(self::COOKIE_NEXT_RESULTS, "", 0);
                    }
                    $rightData[Front::CANVAS_INPUT_NAME] = $this->input->post(Front::CANVAS_INPUT_NAME);
                    $this->load->view('templates/header');
                    $this->load->view('pages/select', $data);
                    $this->load->view('pages/main', $rightData);
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
     * Render default view with canvas and form. Select data from list and set them to form
     */
    public function select() {
        $data = array();
        $data[Front::CANVAS_HIDDEN_DATABASE] = $this->input->post(Front::CANVAS_HIDDEN_DATABASE);

        /* Problem with name */
        $strName = $this->input->post(Front::CANVAS_INPUT_NAME);
        if ($this->input->post(Front::CANVAS_HIDDEN_DATABASE) == ServerEnum::PUBCHEM) {
            $pubChemFinder = new PubChemFinder();
            $pubChemFinder->findName($this->input->post(Front::CANVAS_INPUT_IDENTIFIER), $strName);
            $data[Front::CANVAS_INPUT_NAME] = $strName;
        } else {
            if (!empty($strName)) {
                $data[Front::CANVAS_INPUT_NAME] = $strName;
            } else {
                /* TODO maybe can be deleted with the hidden input */
                $data[Front::CANVAS_INPUT_NAME] = $this->input->post(Front::CANVAS_HIDDEN_NAME);
            }
        }

        $data[Front::CANVAS_INPUT_SMILE] = $this->input->post(Front::CANVAS_INPUT_SMILE);
        $data[Front::CANVAS_INPUT_FORMULA] = $this->input->post(Front::CANVAS_INPUT_FORMULA);
        $data[Front::CANVAS_INPUT_MASS] = $this->input->post(Front::CANVAS_INPUT_MASS);
        $data[Front::CANVAS_INPUT_IDENTIFIER] = $this->input->post(Front::CANVAS_INPUT_IDENTIFIER);
        $this->load->view('templates/header');
        $this->load->view('pages/canvas');
        $this->load->view('pages/main', $data);
        $this->load->view('templates/footer');
    }

    public function next() {
        $arNext = unserialize($this->input->cookie(self::COOKIE_NEXT_RESULTS));
//        $this->input->set_cookie(self::COOKIE_NEXT_RESULTS, "", 0);
        $intDatabase = $this->input->post(Front::CANVAS_HIDDEN_DATABASE);

        var_dump($arNext);

        if (isset($arNext) && !empty($arNext)) {
            $arResult = array_splice($arNext, 0, \Bbdgnc\Finder\IFinder::FIRST_X_RESULTS);

            $finder = new Finder();
            $finder->findByIdentifiers($intDatabase, $arResult, $outArResult);

            $data['molecules'] = $outArResult;
            $rightData = $this->getData();
            $data[Front::CANVAS_HIDDEN_NAME] = $this->input->post(Front::CANVAS_INPUT_NAME);
            if (!empty($arNext)) {
                // save next results to cookie
                $this->input->set_cookie(self::COOKIE_NEXT_RESULTS, serialize($arNext), self::COOKIE_EXPIRE_HOUR);
            }
            $rightData[Front::CANVAS_INPUT_NAME] = $this->input->post(Front::CANVAS_INPUT_NAME);
            $this->load->view('templates/header');
            $this->load->view('pages/select', $data);
            $this->load->view('pages/main', $rightData);
            $this->load->view('templates/footer');
        } else {
            $this->load->view('templates/header');
            $this->load->view('pages/canvas');
            $this->load->view('pages/main', $this->getData());
            $this->load->view('templates/footer');
        }
    }

    /**
     * Find by - specific param
     * @param int $intDatabase where to search
     * @param int $intFindBy find by this param
     * @param array $outArResult output param result only first X resutls, can be influenced by param IFinder::FIRST_X_RESULTS
     * @param array $outArNextResult next results identifiers
     * @return int result code 0 => find none, 1 => find 1, 2 => find more than 1
     */
    private function findBy($intDatabase, $intFindBy, &$outArResult = array(), &$outArNextResult = array(), $arSearchOptions = array()) {
        $finder = new Finder();
        $finder->setOptions($arSearchOptions);
        /* TODO other cases */
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
        }
    }

}
