<?php

namespace Bbdgnc\Smiles\Parser;

class AtomParser implements IParser {

    /**
     * Parse text
     * @param string $strText
     * @return Accept|Reject
     */
    public function parse($strText) {
        if (empty($strText) || !isset($strText)) {
            return self::reject();
        }
        $intIndex = 0;
        if (!ctype_alpha($strText[$intIndex])) {
            return self::reject();
        }
        $strName = "";
        $intLength = strlen($strText);
        while (ctype_alpha($strText[$intIndex])) {
            if ($intIndex > 0 && ctype_upper($strText[$intIndex])) {
                return new Accept($strName, substr($strText, $intIndex));
            }
            $strName .= $strText[$intIndex];
            $intIndex++;
            if ($intIndex >= $intLength) {
                return new Accept($strName, '');
            }
        }
        return new Accept($strName, substr($strText, $intIndex));
    }

    /**
     * Get instance of Reject
     * @return Reject
     */
    public static function reject() {
        return new Reject('Not match Atom in []');
    }
}