<?php

namespace Bbdgnc\Mapper;

use Bbdgnc\TransportObjects\IEntity;
use Bbdgnc\TransportObjects\ITransportObject;

interface IToMapper {
    public function toEntity(ITransportObject $transportObject);
    public function toTransportObject(IEntity $entity);
}