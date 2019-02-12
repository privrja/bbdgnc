<?php

namespace Bbdgnc\Smiles\Parser;

use Bbdgnc\TransportObjects\ReferenceTO;

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
            return self::reject();
        }
        $reference = new ReferenceTO();
        $reference->server = $serverResult->getResult();
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
