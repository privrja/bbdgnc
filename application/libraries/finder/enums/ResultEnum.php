<?php

namespace Bbdgnc\Finder\Enum;

/**
 * Class ResultEnum
 * Enum for three types of results from class, implements IFinder
 * @package Bbdgnc\Finder\Enum
 */
abstract class ResultEnum {

    /** @var int Nothing found */
    const REPLY_NONE = 0;

    /** @var int Found 1 result */
    const REPLY_OK_ONE = 1;

    /** @var int found more than 1 result */
    const REPLY_OK_MORE = 2;
}