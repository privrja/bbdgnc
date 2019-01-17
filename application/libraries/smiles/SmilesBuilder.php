<?php

namespace Bbdgnc\Smiles;


use Bbdgnc\Exception\IllegalArgumentException;

class SmilesBuilder {

    private $arSmiles = [];

    /** @var string[] $arBadInput */
    private $arBadInput = [];

    /**
     * SmilesBuilder constructor.
     * @param string $strText
     */
    public function __construct($strText) {
        $arTexts = $this->prepareTexts($strText);
        foreach ($arTexts as $strSmiles) {
            try {
                $this->arSmiles[] = new Smiles($strSmiles); // TODO is it OK when parse error?
            } catch (IllegalArgumentException $exception) {
                $this->arBadInput[] = $strSmiles;
            }
        }
    }

    public function getSmiles() {
        return $this->arSmiles;
    }

    /**
     * Split string to array by dot separator
     * @param string $strText
     * @return array
     */
    private function prepareTexts($strText) {
        return explode('.', $this->removeWhiteSpace($strText));
    }

    /**
     * Remove all whitespace from string
     * @param string $strText
     * @return null|string|string[]
     */
    private function removeWhiteSpace($strText) {
        $strTrimText = preg_replace('/\s+/', '', $strText);
        return !isset($strTrimText) ? $strText : $strTrimText;
    }

}
