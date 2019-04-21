<?php

use Bbdgnc\Base\HelperEnum;
use Bbdgnc\Base\ModelEnum;
use Bbdgnc\CycloBranch\BlockCycloBranch;
use Bbdgnc\CycloBranch\BlockWithoutFormulaCycloBranch;
use Bbdgnc\CycloBranch\ModificationCycloBranch;
use Bbdgnc\CycloBranch\SequenceCycloBranch;
use Bbdgnc\Enum\Front;

class Export extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper([HelperEnum::HELPER_URL, HelperEnum::HELPER_DOWNLOAD]);
        $this->load->model(ModelEnum::BLOCK_MODEL);
        $this->load->model(ModelEnum::SEQUENCE_MODEL);
        $this->load->model(ModelEnum::MODIFICATION_MODEL);
    }

    public function index() {
        $this->load->view(Front::TEMPLATES_HEADER);
        $this->load->view('export/index');
        $this->load->view(Front::TEMPLATES_FOOTER);
    }

    public function blockFormula() {
        $blockExport = new BlockCycloBranch($this);
        $blockExport->export();
    }

    public function block() {
        $blockExport = new BlockWithoutFormulaCycloBranch($this);
        $blockExport->export();
    }

    public function modification() {
        $blockExport = new ModificationCycloBranch($this);
        $blockExport->export();
    }

    public function sequence() {
        $blockExport = new SequenceCycloBranch($this);
        $blockExport->export();
    }

}
