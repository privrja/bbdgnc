<?php

namespace Bbdgnc\Smiles\Parser;

use Bbdgnc\Finder\Enum\ServerEnum;
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
        return new Accept(new ReferenceTO(ServerEnum::NORINE, $norineIdResult->getResult()), $norineIdResult->getRemainder());
    }

    /**
     * Get instance of Reject
     * @return Reject
     */
    public static function reject() {
        return new Reject('Not match : NORINE id');
    }

}
