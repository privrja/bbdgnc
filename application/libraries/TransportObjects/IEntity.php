<?php

namespace Bbdgnc\TransportObjects;

interface IEntity {

    /**
     * Map entity to array for store to database
     * @return array
     */
    function asEntity();
}