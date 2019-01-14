<?php

namespace Bbdgnc\Smiles\parsers;

use Bbdgnc\Enum\PeriodicTableSingleton;

class OrganicSubsetParser implements IParser {

    const LITERALS = ["Br", "Cl", "B", "C", "N", "O", "P", "S", "F", "I"];

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
                return new Accept(PeriodicTableSingleton::getInstance()->getAtoms()[$LITERAL], $parseResult->getRemainder());
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
