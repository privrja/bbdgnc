<?php

namespace Bbdgnc\Mapper;

use Bbdgnc\Entity\BlockEntity;
use Bbdgnc\TransportObjects\BlockTO;
use Bbdgnc\TransportObjects\IEntity;
use Bbdgnc\TransportObjects\ITransportObject;

class BlockMapper implements IToMapper {

    public function toEntity(ITransportObject $blockTO) {
        $blockEntity = new BlockEntity();


    }

    public function toTransportObject(IEntity $entity) {
        // TODO: Implement toTransportObject() method.
    }
}