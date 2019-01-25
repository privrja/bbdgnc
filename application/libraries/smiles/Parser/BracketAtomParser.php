<?php

namespace Bbdgnc\Smiles\Parser;

use Bbdgnc\Smiles\BracketElement;

class BracketAtomParser implements IParser {

    /**
     * Parse text
     * @param string $strText
     * @return Accept|Reject
     */
    public function parse($strText) {
        /** @var BracketElement $element */
        $element = null;
        $leftBracketParser = new LeftSquareBracketParser();
        $leftResult = $leftBracketParser->parse($strText);
        if (!$leftResult->isAccepted()) {
            return self::reject();
        }

        $orgParser = new OrganicSubsetParser();
        $orgResult = $orgParser->parse($leftResult->getRemainder());
        if (!$orgResult->isAccepted()) {
            return self::reject();
        }

        $element = $orgResult->getResult()->asBracketElement();
        $strRemainder = $orgResult->getRemainder();
        $hydrogensParser = new HydrogensParser();
        $hydrogensResult = $hydrogensParser->parse($strRemainder);
        if ($hydrogensResult->isAccepted()) {
            $element->setHydrogens($hydrogensResult->getResult());
            $strRemainder = $hydrogensResult->getRemainder();
        }

        $chargeParser = new ChargeParser();
        $chargeResult = $chargeParser->parse($strRemainder);
        if ($chargeResult->isAccepted()) {
            $element->setCharge($chargeResult->getResult());
            $strRemainder = $chargeResult->getRemainder();
        }

        $rightBracketParser = new RightSquareBracketParser();
        $rightResult = $rightBracketParser->parse($strRemainder);
        if (!$rightResult->isAccepted()) {
            return self::reject();
        }
        return new Accept($element, $rightResult->getRemainder());
    }

    /**
     * Get instance of Reject
     * @return Reject
     */
    public static function reject() {
        return new Reject('Not match atom in []');
    }
}
