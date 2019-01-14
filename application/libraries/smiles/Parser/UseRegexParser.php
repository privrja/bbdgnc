<?php

namespace Bbdgnc\Smiles\Parser;

class UseRegexParser {

    public static function parseTextWithRegexType($strText, $strRegex, IParser $classReference) {
        $regexParser = new RegexParser();
        $result = $regexParser->parseTextWithRegexByLengthOne($strText, $strRegex);
        if ($result->isAccepted()) {
            return $result;
        }
        return $classReference::reject();
    }

}