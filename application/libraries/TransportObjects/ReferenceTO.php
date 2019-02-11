<?php

namespace Bbdgnc\TransportObjects;

use Bbdgnc\Finder\Enum\ServerEnum;

class ReferenceTO {

    /** @var int $server
     * @see ServerEnum
     */
    private $server;

    /** @var mixed $identifier */
    private $identifier;

    /**
     * ReferenceTO constructor.
     * @param int $server
     * @param mixed $identifier
     */
    public function __construct(int $server, $identifier) {
        assert($server <= ServerEnum::CHEBI);
        assert($server >= 0);
        $this->server = $server;
        $this->identifier = $identifier;
    }

}
