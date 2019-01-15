<?php

namespace Bbdgnc\Smiles\Parser;

use Bbdgnc\Smiles\Bond;
use Bbdgnc\Smiles\Enum\BondTypeEnum;
use Bbdgnc\Smiles\Graph;

class SmilesParser implements IParser {

    private $graph;
    private $strSmiles;
    private $intNodeIndex;
    private $lastBond;
    private $intLeftBraces;
    private $intRightBraces;
    private $arNodesBeforeBracket;
    private $bondParser;
    private $orgParser;
    private $leftBracketParser;
    private $rightBracketParser;

    /**
     * SmilesParser constructor.
     * @param Graph $graph
     */
    public function __construct(Graph $graph) {
        $this->graph = $graph;
        $this->bondParser = new BondParser();
        $this->orgParser = new OrganicSubsetParser();
        $this->leftBracketParser = new LeftBracketParser();
        $this->rightBracketParser = new RightBracketParser();
    }

    public function initialize($strText) {
        $this->strSmiles = $strText;
        $this->intNodeIndex = $this->intLeftBraces = $this->intRightBraces = 0;
        $this->arNodesBeforeBracket = [];
        $this->lastBond = null;
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
        $this->initialize($strText);
        while ($this->strSmiles != '') {
//            if ($intNodeIndex >= 25) break;
            $orgResult = $this->orgParser->parse($this->strSmiles);
            if ($orgResult->isAccepted()) {
                $this->graph->addNode($orgResult->getResult());
                if ($this->intNodeIndex > 0) {
                    $this->graph->addBond($this->intNodeIndex - 1, new Bond($this->intNodeIndex, $this->lastBond));
                    $this->graph->addBond($this->intNodeIndex, new Bond($this->intNodeIndex - 1, $this->lastBond));
                }
                // try bond
                $bondResult = $this->bondParser->parse($orgResult->getRemainder());
                if (!$bondResult->isAccepted()) {
                    var_dump("BREAK III");
                    $this->strSmiles = $orgResult->getRemainder();
                } else {
                    $this->lastBond = $bondResult->getResult();

                    // try ( || [ || Organic subset if it's ( this bond is wrong -> need to be only -, need to parse again after bracket


                    $leftBracketResult = $this->leftBracketParser->parse($bondResult->getRemainder());
                    if ($leftBracketResult->isAccepted()) {
//                    var_dump("(");
                        $this->intLeftBraces++;
                        // problem try bond if last bond is multiple => error
                        if (BondTypeEnum::isMultipleBinding($bondResult->getResult())) {
                            return self::reject();
                        }
                        $this->parseAndCallBack($leftBracketResult->getRemainder(), $this->bondParser, 'bondAfterBracketOk', 'bondAfterBracketKo');
                    } else {
                        // no ( test [ || Organic Subset again -> continue in cycle
                        $this->strSmiles = $bondResult->getRemainder();
                        $this->intNodeIndex++;
                    }
                }
            } else if (!empty($this->arNodesBeforeBracket)) {
                $rightBracketResult = $this->rightBracketParser->parse($this->strSmiles);
                if ($rightBracketResult->isAccepted()) {
//                    var_dump(")");
                    $this->intRightBraces++;
                    // there can be bond
                    $bondAfterBracketResult = $this->bondParser->parse($rightBracketResult->getRemainder());
                    if (!$bondAfterBracketResult->isAccepted()) {
                        var_dump("BREAK I");
                        $this->strSmiles = $rightBracketResult->getRemainder();
                    } else {
                        // removed unnecessary parenthesis before start
                        // there can be [ || organic subset but i need to parse it now no going to new cycle
                        $orgAfterBracket = $this->orgParser->parse($bondAfterBracketResult->getRemainder());
                        if ($orgAfterBracket->isAccepted()) {
                            $intNodeAfterBracketIndex = array_pop($this->arNodesBeforeBracket);
                            $this->graph->addNode($orgAfterBracket->getResult());
                            $this->graph->addBond($intNodeAfterBracketIndex, new Bond($this->intNodeIndex, $bondAfterBracketResult->getResult()));
                            $this->graph->addBond($this->intNodeIndex, new Bond($intNodeAfterBracketIndex, $bondAfterBracketResult->getResult()));
                            $this->strSmiles = $orgAfterBracket->getRemainder();
                            $this->intNodeIndex++;
                        } else {
                            // there can be [ || ) if not remove unnecessary parentheses otherwise reject
                            var_dump("reject I");
                            return self::reject();
                        }
                    }
                } else {
                    var_dump("reject II");
                    return self::reject();
                }
            } else {
//                var_dump($this->strSmiles);
                var_dump("reject III");
                return self::reject();
            }
        }
        return $this->intLeftBraces == $this->intRightBraces ? new Accept($this->graph, '') : self::reject();
    }

    private function parseAndCallBack(string $strText, IParser $parser, $funcOk, $funcKo) {
        $result = $parser->parse($strText);
        if ($result->isAccepted()) {
            $this->$funcOk($result);
        } else {
            $this->$funcKo($strText);
        }
    }

    private function bondAfterBracketOk(ParseResult $result) {
        var_dump('ok');
        $this->lastBond = $result->getResult();
        $this->arNodesBeforeBracket[] = $this->intNodeIndex;
        $this->strSmiles = $result->getRemainder();
        $this->intNodeIndex++;
    }

    private function bondAfterBracketKo(string $strText) {
        var_dump('ko');
        $this->strSmiles = $strText;
    }

    /**
     * Get instance of Reject
     * @return Reject
     */
    public static function reject() {
        return new Reject('Not match SMILES');
    }
}