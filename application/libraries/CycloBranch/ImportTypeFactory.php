<?php

class ImportTypeFactory {

    /**
     * @param int $type
     * @return ICycloBranch
     */
    public static function getCycloBranch(int $type) {
        switch ($type) {
            case ImportTypeEnum::SEQUENCE:
                return new SequenceAbstractCycloBranch();
                break;
            case ImportTypeEnum::BLOCK:
                return new BlockAbstractCycloBranch();
                break;
            case ImportTypeEnum::MODIFICATION:
                return new ModificationAbstractCycloBranch();
                break;
        }

    }

}
