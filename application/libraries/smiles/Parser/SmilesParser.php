<?php

namespace Bbdgnc\Smiles\Parser;

use Bbdgnc\Enum\PeriodicTableSingleton;
use Bbdgnc\Smiles\Bond;
use Bbdgnc\Smiles\Enum\BondTypeEnum;
use Bbdgnc\Smiles\Graph;

class SmilesParser implements IParser {

    private $graph;

    /**
     * SmilesParser constructor.
     * @param Graph $graph
     */
    public function __construct(Graph $graph) {
        $this->graph = $graph;
    }

    /**
     * Parse text
     * - remove all whitespace from text
     * - split by dots
     * - parse splited strings to graph
     * @param string $strText
     * @return Accept|Reject
     */
    public function parse($strText) {
        $orgParser = new OrganicSubsetParser();
        $bondParser = new BondParser();
        $leftBracketParser = new LeftBracketParser();
        $rightBracketParser = new RightBracketParser();
        $strSmiles = $strText;
        $arNodesBeforeBracket = [];
        $intNodeIndex = 0;
        while (true) {
            if ($strSmiles == '') {
                break;
            }

            $orgResult = $orgParser->parse($strSmiles);
            if ($orgResult->isAccepted()) {
                var_dump($orgResult->getResult());
                $this->graph->addNode($orgResult->getResult());
                // try bond
                $bondResult = $bondParser->parse($orgResult->getRemainder());
                if (!$bondResult->isAccepted()) {
                    // null or end of string
                    return self::reject();
                }
                // try ( || [ || Organic subset if it's ( this bond is wrong -> need to be only -, need to parse again after bracket
                $leftBracketResult = $leftBracketParser->parse($bondResult->getRemainder());
                if ($leftBracketResult->isAccepted()) {
                    // problem try bond if last bond is multiple => error
                    if (BondTypeEnum::isMultipleBinding($bondResult->getResult())) {
                        return self::reject();
                    }

                    $bondResultBracket = $bondParser->parse($leftBracketResult->getRemainder());
                    if ($bondResultBracket->isAccepted()) {
                        // bond found so parse [ || organic subset -> continue in cycle
                        $arNodesBeforeBracket[] = $intNodeIndex;
                        $strSmiles = $bondResult->getRemainder();
                        continue;
                    }
                    // no bond found so parse [ || organic subset -> continue in cycle
                    $strSmiles = $leftBracketResult->getRemainder();
                    continue;
                }
                // no ( test [ || Organic Subset again -> continue in cycle
                $strSmiles = $bondResult->getRemainder();
                $intNodeIndex++;
                continue;
            }
            // try [ || )
            if (!empty($arNodesBeforeBracket)) {
                $rightBracketResult = $rightBracketParser->parse($strSmiles);
                if ($rightBracketResult->isAccepted()) {
                    // there can be bond
                    $bondAfterBracketResult = $bondParser->parse($rightBracketResult->getRemainder());
                    if (!$bondAfterBracketResult->isAccepted()) {
                        // null or end of string
                        return self::reject();
                    }

                    // removed unnecessary parenthesis before start
                    // there can be [ || organic subset but i need to parse it now no going to new cycle
                    $orgAfterBracket = $orgParser->parse($bondAfterBracketResult->getRemainder());
                    if ($orgAfterBracket->isAccepted()) {
                        $intNodeAfterBracketIndex = array_pop($arNodesBeforeBracket);
                        $this->graph->addNode(PeriodicTableSingleton::getInstance()[$orgAfterBracket->getResult()]);
                        $this->graph->addBond($intNodeAfterBracketIndex, new Bond($intNodeIndex, $bondAfterBracketResult->getResult()));
                        $this->graph->addBond($intNodeIndex, new Bond($intNodeAfterBracketIndex, $bondAfterBracketResult->getResult()));
                        $intNodeIndex++;
                    }
                    // what here?

                }
                continue;
            }

            return self::reject();
        }
        return self::reject();
    }

    /**
     * Get instance of Reject
     * @return Reject
     */
    public static function reject() {
        return new Reject('Not match SMILES');
    }
}