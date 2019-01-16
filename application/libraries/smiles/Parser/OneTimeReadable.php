<?php

namespace Bbdgnc\Smiles\Parser;

use Bbdgnc\Smiles\Exception\ReadOnlyOneTimeException;

class OneTimeReadable {

    /** @var mixed stored object */
    private $object;

    /** @var bool indicating times of read ('Zero' = false or 'More times' = true) */
    private $read = false;

    /**
     * Get Stored Object
     * @return mixed stored object
     * @throws ReadOnlyOneTimeException when object has been already read
     */
    public function getObject() {
        if (!$this->read) {
            throw new ReadOnlyOneTimeException();
        }
        $this->read = false;
        return $this->object;
    }

    /**
     * @see OneTimeReadable::$read
     * @return bool
     */
    public function isRead() {
        return $this->read;
    }

}
