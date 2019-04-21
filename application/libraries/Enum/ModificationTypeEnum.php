<?php

namespace Bbdgnc\Enum;

class ModificationTypeEnum {

    const N_MODIFICATION = 'n';

    const C_MODIFICATION = 'c';

    const BRANCH_MODIFICATION = 'b';

    public function validate(string $modificationType) {
        return !($modificationType !== self::N_MODIFICATION || $modificationType !== self::C_MODIFICATION || $modificationType !== self::BRANCH_MODIFICATION);
    }

}
