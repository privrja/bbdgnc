<?php

namespace Bbdgnc\Smiles\Parser;

class HydrogensParser implements IParser {

    /**
     * Parse text
     * @param string $strText
     * @return Accept|Reject
     */
    public function parse($strText) {
        $hydrogenParser = new HydrogenParser();
        $hydrogenResult = $hydrogenParser->parse($strText);
        if (!$hydrogenResult->isAccepted()) {
            return self::reject();
        }
        $natParser = new NatParser();
        $natResult = $natParser->parse($hydrogenResult->getRemainder());
        if (!$natResult->isAccepted()) {
            return new Accept(1, $hydrogenResult->getRemainder());
        }
        return $natResult;
    }

    /**
     * Get instance of Reject
     * @return Reject
     */
    public static function reject() {
        return new Reject('Not match hydrogen and number');
    }
}