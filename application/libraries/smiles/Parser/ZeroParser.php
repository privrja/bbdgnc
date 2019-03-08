<?php

namespace Bbdgnc\Smiles\Parser;

use Bbdgnc\Finder\IFinder;

class ZeroParser implements IParser {
    /**
     * Parse text
     * @param string $strText
     * @return Accept|Reject
     */
    public function parse($strText) {
        return UseRegexParser::parseTextWithRegexType($strText, '/0/', $this);
    }

    /**
     * Get instance of Reject
     * @return Reject
     */
    public static function reject() {
        return new Reject('Not match 0');
    }
}