<?php

namespace Bbdgnc\CycloBranch;

use Bbdgnc\CycloBranch\Enum\ImportTypeEnum;
use CI_Controller;

class ImportTypeFactory {

    /**
     * Get right instance of AbstractCycloBranch for import
     * @param int $type
     * @see ImportTypeEnum
     * @param CI_Controller $controller
     * @return AbstractCycloBranch
     */
    public static function getCycloBranch(int $type, CI_Controller $controller) {
        switch ($type) {
            case ImportTypeEnum::SEQUENCE:
                return new SequenceCycloBranch($controller);
            case ImportTypeEnum::BLOCK:
                return new BlockCycloBranch($controller);
            case ImportTypeEnum::MODIFICATION:
                return new ModificationCycloBranch($controller);
            default:
                return new BlockCycloBranch($controller);
        }
    }

}
