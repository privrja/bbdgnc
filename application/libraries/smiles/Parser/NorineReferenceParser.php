<?php

namespace Bbdgnc\Smiles\Parser;

use Bbdgnc\TransportObjects\ReferenceTO;

class NorineReferenceParser implements IParser {

    /**
     * Parse text
     * @param string $strText
     * @return Accept|Reject
     */
    public function parse($strText) {
        $norineParser = new NorineParser();
        $norineResult = $norineParser->parse($strText);
        if (!$norineResult->isAccepted()) {
            return self::reject();
        }
        $norineIdParser = new NorineIdParser();
        $norineIdResult = $norineIdParser->parse($norineResult->getRemainder());
        if (!$norineIdResult->isAccepted()) {
            return self::reject();
        }
        $reference = new ReferenceTO();
        $reference->database = $norineResult->getResult();
        $reference->identifier = $norineIdResult->getResult();
        return new Accept($reference, $norineIdResult->getRemainder());
    }

    /**
     * Get instance of Reject
     * @return Reject
     */
    public static function reject() {
        return new Reject('Not match : NORINE id');
    }

}
