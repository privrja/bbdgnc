<?php

namespace Bbdgnc\Smiles\Parser;

use Bbdgnc\Finder\Enum\ServerEnum;
use Bbdgnc\TransportObjects\ReferenceTO;

class SmilesReferenceParser implements IParser {

    /**
     * Parse text
     * @param string $strText
     * @return Accept|Reject
     */
    public function parse($strText) {
        $parser = new StringParser();
        $result = $parser->parseTextWithTemplate($strText, 'SMILES: ');
        if (!$result->isAccepted()) {
            return self::reject();
        }

        if (preg_match('/^\S+/', $result->getRemainder(), $matches)) {
            $length = strlen($matches[0]);
            $reference = new ReferenceTO();
            $reference->database = "SMILES";
            $reference->identifier = $matches[0];
            return new Accept($reference, substr($result->getRemainder(), $length));
        }
        return self::reject();
    }

    /**
     * Get instance of Reject
     * @return Reject
     */
    public static function reject() {
        return new Reject('Not match SMILES: <SMLIES>');
    }

}
