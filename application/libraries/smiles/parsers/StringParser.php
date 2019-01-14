<?php

namespace Bbdgnc\Smiles\parsers;

use Bbdgnc\Exception\IllegalArgumentException;

class StringParser {

    public function parseTextWithTemplate($strText, $strTemplate) {
        if (!isset($strTemplate)) {
            throw new IllegalArgumentException();
        }
        if (empty($strTemplate) && empty($strText)) {
            return new Accept('', '');
        } else if (empty($strTemplate)) {
            return new Reject('Not match template');
        }

        if (preg_match('/^' . $strTemplate . '/', $strText)) {
            return new Accept($strTemplate, substr($strText, strlen($strTemplate)));
        }
        return new Reject('Not match template');
    }

}