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
        $norineIdParser = new NorineIdParser();
        $norineIdResult = $norineIdParser->parse($strText);
        if (!$norineIdResult->isAccepted()) {
            return self::reject();
        }
        $reference = new ReferenceTO();
        $reference->database = ServerEnum::NORINE;
        $reference->identifier = $norineIdResult->getResult();
        return new Accept($reference, $norineIdResult->getRemainder());
    }

    /**
     * Get instance of Reject
     * @return Reject
     */
    public static function reject() {
        return new Reject('Not match NORINE id');
    }

}
