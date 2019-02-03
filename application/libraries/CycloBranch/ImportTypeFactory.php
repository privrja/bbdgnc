<?php

class ImportTypeFactory {

    /**
     * @param int $type
     * @return ICycloBranch
     */
    public static function getCycloBranch(int $type) {
        switch ($type) {
            case ImportTypeEnum::SEQUENCE:
                return new SequenceCycloBranch();
                break;
            case ImportTypeEnum::BLOCK:
                return new BlockCycloBranch();
                break;
            case ImportTypeEnum::MODIFICATION:
                return new ModificationCycloBranch();
                break;
        }

    }

}
