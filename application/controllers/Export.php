<?php

use Bbdgnc\Base\HelperEnum;
use Bbdgnc\Base\ModelEnum;
use Bbdgnc\CycloBranch\BlockCycloBranch;

class Export extends CI_Controller {


    public function __construct() {
        parent::__construct();
        $this->load->helper([HelperEnum::HELPER_URL, HelperEnum::HELPER_DOWNLOAD]);
        $this->load->model(ModelEnum::BLOCK_MODEL);
        $this->load->model(ModelEnum::SEQUENCE_MODEL);
        $this->load->model(ModelEnum::MODIFICATION_MODEL);
    }

    public function index() {
        $blockExport = new BlockCycloBranch($this);
        $blockExport->export();
    }


}