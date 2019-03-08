<?php

namespace Bbdgnc\Smiles\Parser;

use Bbdgnc\TransportObjects\ReferenceTO;
use phpDocumentor\Reflection\Types\Self_;

class ServerNumReferenceParser implements IParser {

    /**
     * Parse text
     * @param string $strText
     * @return Accept|Reject
     */
    public function parse($strText) {
        $serverNumParser = new ServerNumParser();
        $serverResult = $serverNumParser->parse($strText);
        if (!$serverResult->isAccepted()) {
            return self::reject();
        }
        $numberParser = new NatParser();
        $numberResult = $numberParser->parse($serverResult->getRemainder());
        if (!$numberResult->isAccepted()) {
            $zeroParser = new ZeroParser();
            $zeroResult = $zeroParser->parse($serverResult->getRemainder());
            if ($zeroResult->isAccepted()) {
                $reference = new ReferenceTO();
                $reference->database = null;
                $reference->identifier = null;
                return new Accept($reference, $zeroResult->getRemainder());
            }
            return self::reject();
        }
        $reference = new ReferenceTO();
        $reference->database = $serverResult->getResult();
        $reference->identifier = $numberResult->getResult();
        return new Accept($reference, $numberResult->getRemainder());
    }

    /**
     * Get instance of Reject
     * @return Reject
     */
    public static function reject() {
        return new Reject('Not match CSID: number | CID: number');
    }

}
