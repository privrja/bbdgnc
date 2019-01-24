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
        $strName = "";
        $intIndex = 0;
        $intLength = strlen($strText);
        while (!is_numeric($strText[$intIndex])) {
            // TODO +
            if ($intIndex > 0 && ctype_upper($strText[$intIndex])) {
                return new Accept($strName, substr($strText, $intIndex));
            }
            $strName .= $strText[$intIndex];
            $intIndex++;
            if ($intIndex >= $intLength) {
                return self::reject();
            }
        }
        return new Accept($strName, substr($strText, $intIndex));
//        $stringParser = new StringParser();
//        foreach (PeriodicTableSingleton::getInstance()->getAtoms() as $atom => $value) {
//            $result = $stringParser->parseTextWithTemplate($strText, $atom);
//            if ($result->isAccepted()) {
//                return $result;
//            }
//        }
//        return self::reject();
    }

    /**
     * Get instance of Reject
     * @return Reject
     */
    public static function reject() {
        return new Reject('Not match Atom in []');
    }
}