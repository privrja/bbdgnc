<?php

namespace Bbdgnc\Base;


class BlockSplObjectStorage extends \SplObjectStorage {

    public function getHash($object) {
        return $object->acronym;
    }

}
