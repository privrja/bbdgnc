<?php

namespace Bbdgnc\Base;


/**
 * Class BlockSplObjectStorage
 * extend SplObjectStorage and setup hash for comparing
 * @package Bbdgnc\Base
 */
class BlockSplObjectStorage extends \SplObjectStorage {

    public function getHash($object) {
        return $object->acronym;
    }

}
