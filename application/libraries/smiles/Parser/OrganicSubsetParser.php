<?php

namespace Bbdgnc\Smiles\Parser;

class OrganicSubsetParser implements IParser {

    const LITERALS = ["Br", "Cl", "B", "C", "N", "O", "P", "S", "F", "I", "br", "cl", "b", "c", "n", "o", "p", "s", "f", "i"];

    /**
     * Parse text
     * @param string $strText
     * @return Accept|Reject
     */
    public function parse($strText) {
        $stringParser = new StringParser();
        foreach (self::LITERALS as $LITERAL) {
            $parseResult = $stringParser->parseTextWithTemplate($strText, $LITERAL);
            if ($parseResult->isAccepted()) {
                return new Accept($LITERAL, $parseResult->getRemainder());
            }
        }
        return self::reject();
    }

    /**
     * Get instance of Reject
     * @return Reject
     */
    public static function reject() {
        return new Reject('Not match Organic Subset');
    }
}
