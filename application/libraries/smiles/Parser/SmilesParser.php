<?php

namespace Bbdgnc\Smiles\Parser;

use Bbdgnc\Base\OneTimeReadable;
use Bbdgnc\Exception\ReadOnlyOneTimeException;
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

    /** @var OneTimeReadable[] */
    private $arNumberBonds;
    private $bondParser;
    private $orgParser;
    private $leftBracketParser;
    private $rightBracketParser;
    private $natParser;

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
        $this->natParser = new FirstDigitParser();
    }

    public function initialize($strText) {
        $this->strSmiles = $strText;
        $this->intNodeIndex = $this->intLeftBraces = $this->intRightBraces = 0;
        $this->arNodesBeforeBracket = $this->arNumberBonds = [];
        $this->lastBond = null;
    }

    private function addBonds(int $from, int $to) {
        $this->graph->addBond($from, new Bond($to, $this->lastBond));
        $this->graph->addBond($to, new Bond($from, $this->lastBond));
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
                $this->parseAndCallBack(self::accept(), $this->orgParser, 'firstOrgOk', 'backFromBracket');
            }
        } catch (RejectException | ReadOnlyOneTimeException $exception) {
            return self::reject();
        }
        return $this->intLeftBraces == $this->intRightBraces ? new Accept($this->graph, '') : self::reject();
    }

    private function tryNumberOk(ParseResult $result, ParseResult $lastResult) {
        // save data
        if (isset($this->arNumberBonds[$result->getResult()])) {
            $intWhere = $this->arNumberBonds[$result->getResult()]->getObject();
            $this->addBonds($intWhere, $this->intNodeIndex - 1);
        } else {
            $this->arNumberBonds[$result->getResult()] = new OneTimeReadable($this->intNodeIndex - 1);
        }
        $this->parseAndCallBack($result, $this->natParser, 'tryNumberOk', 'tryBond');
    }

    private function tryBond(ParseResult $result, ParseResult $lastResult) {
        $this->parseAndCallBack($lastResult, $this->bondParser, 'bondXOk', 'next');
    }

    private function bondXOk(ParseResult $result, ParseResult $lastResult) {
        $this->lastBond = $result->getResult();
        $this->parseAndCallBack($result, $this->leftBracketParser, 'leftBracketXOk', 'next');
    }

    private
    function leftBracketXOk(ParseResult $result, ParseResult $lastResult) {
        $this->intLeftBraces++;
        if (BondTypeEnum::isMultipleBinding($lastResult->getResult())) {
            throw new RejectException();
        }
        $this->parseAndCallBack($result, $this->bondParser, 'bondAfterBracketXOk', 'next');
    }

    private
    function next(ParseResult $result, ParseResult $lastResult) {
        $this->strSmiles = $lastResult->getRemainder();
    }

    private
    function bondAfterBracketXOk(ParseResult $result, ParseResult $lastResult) {
        $this->lastBond = $result->getResult();
        $this->arNodesBeforeBracket[] = $this->intNodeIndex - 1;
        $this->strSmiles = $result->getRemainder();
    }

    private
    function firstOrgOk(ParseResult $result, ParseResult $lastResult) {
        //save data
        $this->graph->addNode($result->getResult());
        if ($this->intNodeIndex > 0) {
            $this->addBonds($this->intNodeIndex - 1, $this->intNodeIndex);
        }
        $this->intNodeIndex++;
        // try parse number
        $this->parseAndCallBack($result, $this->natParser, 'tryNumberOk', 'tryBond');
    }

    private
    function backFromBracket(ParseResult $result, ParseResult $lastResult) {
        if (!empty($this->arNodesBeforeBracket)) {
            $this->parseAndCallBack(self::accept(), $this->rightBracketParser, 'rightBracketXOk', 'ko');
        } else {
            var_dump("reject III");
            throw new RejectException();
        }
    }

    private
    function orgAfterBracketXOk(ParseResult $result, ParseResult $lastResult) {
        $intNodeAfterBracketIndex = array_pop($this->arNodesBeforeBracket);
        $this->graph->addNode($result->getResult());
        $this->addBonds($intNodeAfterBracketIndex, $this->intNodeIndex);
        $this->intNodeIndex++;
        $this->parseAndCallBack($result, $this->natParser, 'tryNumberOk', 'tryBond');
    }

    private
    function ko(ParseResult $result, ParseResult $lastResult) {
        throw new RejectException();
    }

    private
    function bondAfterRightBracketXOk(ParseResult $result, ParseResult $lastResult) {
        $this->parseAndCallBack($result, $this->orgParser, 'orgAfterBracketXOk', 'ko');
    }

    private
    function rightBracketXOk(ParseResult $result, ParseResult $lastResult) {
        $this->intRightBraces++;
        $this->parseAndCallBack($result, $this->bondParser, 'bondAfterRightBracketXOk', 'next');
    }

    private
    function parseAndCallBack(ParseResult $lastResult, IParser $parser, string $funcOk, string $funcKo) {
        $result = $parser->parse($lastResult->getRemainder());
        if ($result->isAccepted()) {
            $this->$funcOk($result, $lastResult);
        } else {
            $this->$funcKo($result, $lastResult);
        }
    }

    /**
     * Get instance of Reject
     * @return Reject
     */
    public
    static function reject() {
        return new Reject('Not match SMILES');
    }

    private
    function accept() {
        return new Accept('', $this->strSmiles);
    }
}