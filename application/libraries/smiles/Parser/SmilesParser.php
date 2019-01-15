<?php

namespace Bbdgnc\Smiles\Parser;

use Bbdgnc\Smiles\Bond;
use Bbdgnc\Smiles\Enum\BondTypeEnum;
use Bbdgnc\Smiles\Exception\RejectException;
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
        try {
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
                    $this->parseAndCallBack($orgResult, $this->bondParser, 'tryBondOk', 'tryBondKo');
                } else if (!empty($this->arNodesBeforeBracket)) {
                    $this->parseAndCallBack(new Accept('', $this->strSmiles), $this->rightBracketParser, 'rightBracketOk', 'rightBracketKo');
                } else {
//                    var_dump($this->strSmiles);
                    var_dump("reject III");
                    throw new RejectException();
                }
            }
        } catch (\Exception $exception) {
            return self::reject();
        }
        return $this->intLeftBraces == $this->intRightBraces ? new Accept($this->graph, '') : self::reject();
    }

    private function parseAndCallBack(ParseResult $lastResult, IParser $parser, $funcOk, $funcKo) {
        $result = $parser->parse($lastResult->getRemainder());
        if ($result->isAccepted()) {
            $this->$funcOk($result, $lastResult);
        } else {
            $this->$funcKo($result, $lastResult);
        }
    }

    private function rightBracketOk(ParseResult $result, ParseResult $lasResult) {
        // var_dump(")");
        $this->intRightBraces++;
        // there can be bond
        $this->parseAndCallBack($result, $this->bondParser, 'bondAfterRightBracketOk', 'bondAfterRightBracketKo');
    }

    private function rightBracketKo(ParseResult $result, ParseResult $lasResult) {
        var_dump("reject II");
        throw new RejectException();
    }

    private function bondAfterRightBracketOk(ParseResult $result, ParseResult $lasResult) {
        // removed unnecessary parenthesis before start
        // there can be [ || organic subset but i need to parse it now no going to new cycle
        $this->parseAndCallBack($result, $this->orgParser, 'orgAfterBracketOk', 'orgAfterBracketKo');
    }

    private function bondAfterRightBracketKo(ParseResult $result, ParseResult $lasResult) {
        var_dump("BREAK I");
        $this->strSmiles = $lasResult->getRemainder();
    }

    private function orgAfterBracketOk(ParseResult $result, ParseResult $lasResult) {
        $intNodeAfterBracketIndex = array_pop($this->arNodesBeforeBracket);
        $this->graph->addNode($result->getResult());
        $this->graph->addBond($intNodeAfterBracketIndex, new Bond($this->intNodeIndex, $lasResult->getResult()));
        $this->graph->addBond($this->intNodeIndex, new Bond($intNodeAfterBracketIndex, $lasResult->getResult()));
//        var_dump("here " . $result->getRemainder());
//        var_dump($this->arNodesBeforeBracket);
        $this->parseAndCallBack($result, $this->bondParser, 'tryBondOk', 'checkBracketKo');
    }

    private function checkBracketKo(ParseResult $result, ParseResult $lasResult) {
        $this->strSmiles = $lasResult->getRemainder();
    }

    private function orgAfterBracketKo(ParseResult $result, ParseResult $lasResult) {
        // there can be [ || ) if not remove unnecessary parentheses otherwise reject
        var_dump("reject I");
        throw new RejectException();
    }

    private function tryBondOk(ParseResult $result, ParseResult $lasResult) {
        $this->lastBond = $result->getResult();
        // try ( || [ || Organic subset if it's ( this bond is wrong -> need to be only -, need to parse again after bracket
//        var_dump($result->getRemainder());
        $this->parseAndCallBack($result, $this->leftBracketParser, 'leftBracketOk', 'leftBracketKo');
    }

    private function tryBondKo(ParseResult $result, ParseResult $lasResult) {
        var_dump("BREAK III");
        $this->strSmiles = $lasResult->getRemainder();
    }

    private function leftBracketOk(ParseResult $result, ParseResult $lastResult) {
        $this->intLeftBraces++;
        // problem try bond if last bond is multiple => error
//        var_dump($lastResult->getResult());
//        var_dump($result->getRemainder());
        if (BondTypeEnum::isMultipleBinding($lastResult->getResult())) {
            throw new RejectException();
        }
        $this->parseAndCallBack($result, $this->bondParser, 'bondAfterBracketOk', 'bondAfterBracketKo');
    }

    private function leftBracketKo(ParseResult $result, ParseResult $lastResult) {
        $this->strSmiles = $lastResult->getRemainder();
        $this->intNodeIndex++;
    }

    private function bondAfterBracketOk(ParseResult $result, ParseResult $lastParserResult) {
        $this->lastBond = $result->getResult();
        $this->arNodesBeforeBracket[] = $this->intNodeIndex;
        $this->strSmiles = $result->getRemainder();
//        var_dump($result->getRemainder());
//        var_dump($this->arNodesBeforeBracket);
//        var_dump($this->intNodeIndex);
        $this->intNodeIndex++;
    }

    private function bondAfterBracketKo(ParseResult $result, ParseResult $lastResult) {
        $this->strSmiles = $lastResult->getRemainder();
    }

    /**
     * Get instance of Reject
     * @return Reject
     */
    public static function reject() {
        return new Reject('Not match SMILES');
    }
}