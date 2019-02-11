<?php

namespace Bbdgnc\TransportObjects;

use Bbdgnc\Finder\Enum\ServerEnum;

class ReferenceTO {

    /** @var int $server
     * @see ServerEnum
     */
    public $server;

    /** @var mixed $identifier */
    public $identifier;

}
