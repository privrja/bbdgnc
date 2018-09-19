<?php

use Bbdgnc\Finder\Finder;
use Bbdgnc\Finder\Enum\FindByEnum;
use Bbdgnc\Enum\Constants;
use Bbdgnc\Finder\Enum\ResultEnum;

class Land extends CI_Controller {

    private $data = array(
        Constants::CANVAS_INPUT_NAME => "", Constants::CANVAS_INPUT_SMILE => "",
        Constants::CANVAS_INPUT_FORMULA => "", Constants::CANVAS_INPUT_MASS => "",
        Constants::CANVAS_INPUT_IDENTIFIER => "", Constants::CANVAS_HIDDEN_DATABASE => ""
    );

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
                case ResultEnum::REPLY_NONE:
                    $this->index();
                    break;
                case ResultEnum::REPLY_OK_ONE:
                    $this->load->view('templates/header');
                    $this->load->view('pages/canvas');
                    $this->load->view('pages/main', $arResult);
                    $this->load->view('templates/footer');
                    break;
                case ResultEnum::REPLY_OK_MORE:
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
     * Render default view with canvas and form. Select data from list and set them to form
     */
    public function select() {
        $data = array();
        $data[Constants::CANVAS_HIDDEN_DATABASE] = $this->input->post(Constants::CANVAS_HIDDEN_DATABASE);
        $data[Constants::CANVAS_INPUT_NAME] = $this->input->post(Constants::CANVAS_INPUT_NAME);
        $data[Constants::CANVAS_INPUT_SMILE] = $this->input->post(Constants::CANVAS_INPUT_SMILE);
        $data[Constants::CANVAS_INPUT_FORMULA] = $this->input->post(Constants::CANVAS_INPUT_FORMULA);
        $data[Constants::CANVAS_INPUT_MASS] = $this->input->post(Constants::CANVAS_INPUT_MASS);
        $data[Constants::CANVAS_INPUT_IDENTIFIER] = $this->input->post(Constants::CANVAS_INPUT_IDENTIFIER);
        $this->load->view('templates/header');
        $this->load->view('pages/canvas');
        $this->load->view('pages/main', $data);
        $this->load->view('templates/footer');
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
                    return ResultEnum::REPLY_NONE;
                }
                return $finder->findByIdentifier($intDatabase, $this->input->post(Constants::CANVAS_INPUT_IDENTIFIER), $outMixResult);
            case FindByEnum::NAME:
                $this->form_validation->set_rules(Constants::CANVAS_INPUT_NAME, "Name", "required");
                if ($this->form_validation->run() === false) {
                    return ResultEnum::REPLY_NONE;
                }
                return $finder->findByName($intDatabase, $this->input->post(Constants::CANVAS_INPUT_NAME), $outMixResult);
        }
    }

}