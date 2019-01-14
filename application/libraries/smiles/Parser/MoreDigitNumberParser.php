<?php

namespace Bbdgnc\Smiles\Parser;

class MoreDigitNumberParser implements IParser {

    /** @var int parsed number must be greater than 10 */
    const MORE_DIGITS = 10;

    /**
     * Parse text
     * @param string $strText
     * @return Accept|Reject
     */
    public function parse($strText) {
        $stringParser = new StringParser();
        $firstPercentResult = $this->parsePercent($stringParser, $strText);
        if (!$firstPercentResult->isAccepted()) {
            return self::reject();
        }
        $natParser = new NatParser();
        $natResult = $natParser->parse($firstPercentResult->getRemainder());
        if (!$natResult->isAccepted() || $natResult->getResult() < 10) {
            return self::reject();
        }
        $secondPercentResult = $this->parsePercent($stringParser, $natResult->getRemainder());
        if (!$secondPercentResult->isAccepted()) {
            return self::reject();
        }
        return new Accept($natResult->getResult(), $secondPercentResult->getRemainder());
    }

    private function parsePercent(StringParser $stringParser, $strText) {
        return $stringParser->parseTextWithTemplate($strText, '%');
    }

    /**
     * Get instance of Reject
     * @return Reject
     */
    public static function reject() {
        return new Reject('Not match %number%');
    }
}