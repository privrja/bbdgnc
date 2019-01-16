<?php

namespace Bbdgnc\Smiles\Parser;

use Bbdgnc\Base\OneTimeReadable;
use Bbdgnc\Exception\ReadOnlyOneTimeException;
use Bbdgnc\Smiles\Enum\BondTypeEnum;
use Bbdgnc\Smiles\Exception\RejectException;
use Bbdgnc\Smiles\Graph;

class SmilesParser implements IParser {

    /** @var Graph $graph */
    private $graph;

    /** @var string $strSmiles control variable for while cycle */
    private $strSmiles;

    /** @var int $intNodeIndex actual node index */
    private $intNodeIndex;

    /** @var string $lastBond last parsed bond */
    private $lastBond;

    /** @var int $intLeftBraces count of '(' */
    private $intLeftBraces;

    /** @var int $intRightBraces count of ')' */
    private $intRightBraces;

    /** @var int $intReading count of numbers read */
    private $intReading;

    /** @var int $intWriting count of numbers stored */
    private $intWriting;

    /** @var int[] $arNodesBeforeBracket stack for nodes before '(' */
    private $arNodesBeforeBracket;

    /** @var OneTimeReadable[] $arNumberBonds */
    private $arNumberBonds;

    /** @var BondParser $bondParser */
    private $bondParser;

    /** @var OrganicSubsetParser $orgParser */
    private $orgParser;

    /** @var LeftBracketParser $leftBracketParser */
    private $leftBracketParser;

    /** @var RightBracketParser $rightBracketParser */
    private $rightBracketParser;

    /** @var SmilesNumberParser $smilesNumberParser */
    private $smilesNumberParser;

    /**
     * SmilesParser constructor.
     * Initialize needed parsers and setup graph
     * @param Graph $graph
     */
    public function __construct(Graph $graph) {
        $this->graph = $graph;
        $this->bondParser = new BondParser();
        $this->orgParser = new OrganicSubsetParser();
        $this->leftBracketParser = new LeftBracketParser();
        $this->rightBracketParser = new RightBracketParser();
        $this->smilesNumberParser = new SmilesNumberParser();
    }

    /**
     * Initialize variables for new parsing
     * @param string $strText
     */
    public function initialize($strText) {
        $this->strSmiles = $strText;
        $this->intNodeIndex = $this->intLeftBraces = $this->intRightBraces = $this->intReading = $this->intWriting = 0;
        $this->arNodesBeforeBracket = $this->arNumberBonds = [];
        $this->lastBond = "";
    }

    /**
     * Parse text
     * - remove all whitespace from text
     * - split by dots
     * - parse spliced strings to graph
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
        return $this->intWriting == $this->intReading && $this->intLeftBraces == $this->intRightBraces
            ? new Accept($this->graph, '') : self::reject();
    }

    /**
     * Smiles number parsed ok ->
     * try to load that number from map
     *  true -> load index of destination node -> can throw exception, can't load more times than one
     *  false -> store number of current node to OneTimeReadable object to map with index of read number from input
     * after that try to load another number, if there no number there should be bond
     * @param ParseResult $result
     * @param ParseResult $lastResult
     * @throws ReadOnlyOneTimeException
     */
    private function tryNumberOk(ParseResult $result, ParseResult $lastResult) {
        if (isset($this->arNumberBonds[$result->getResult()])) {
            $intWhere = $this->arNumberBonds[$result->getResult()]->getObject();
            $this->graph->addBidirectionalBond($intWhere, $this->intNodeIndex - 1, $this->lastBond);
            $this->intReading++;
        } else {
            $this->arNumberBonds[$result->getResult()] = new OneTimeReadable($this->intNodeIndex - 1);
            $this->intWriting++;
        }
        $this->parseAndCallBack($result, $this->smilesNumberParser, 'tryNumberOk', 'tryBond');
    }

    /**
     * Try parse bond
     *  true -> call bondOk
     *  false -> call next
     * @param ParseResult $result
     * @param ParseResult $lastResult
     */
    private function tryBond(ParseResult $result, ParseResult $lastResult) {
        $this->parseAndCallBack($lastResult, $this->bondParser, 'bondOk', 'next');
    }

    /**
     * Bond parse ok ->
     * Set last bond as parsed bond
     * Try to parse '('
     *  true -> call leftBracketOk
     *  false -> next
     * @param ParseResult $result
     * @param ParseResult $lastResult
     */
    private function bondOk(ParseResult $result, ParseResult $lastResult) {
        $this->lastBond = $result->getResult();
        $this->parseAndCallBack($result, $this->leftBracketParser, 'leftBracketOk', 'next');
    }

    /**
     * '{' parsed ok
     * If last bond was more than '-' -> then reject, because of wrong input
     * Try to parse right bond
     *  true -> call bondAfterBracketOk
     *  false -> call next
     * @param ParseResult $result
     * @param ParseResult $lastResult
     * @throws RejectException
     */
    private function leftBracketOk(ParseResult $result, ParseResult $lastResult) {
        $this->intLeftBraces++;
        if (BondTypeEnum::isMultipleBinding($lastResult->getResult())) {
            throw new RejectException();
        }
        $this->parseAndCallBack($result, $this->bondParser, 'bondAfterBracketOk', 'next');
    }

    /**
     * Setup SMILES to last parsed remainder and than go to next cycle of while
     * @param ParseResult $result
     * @param ParseResult $lastResult
     */
    private function next(ParseResult $result, ParseResult $lastResult) {
        $this->strSmiles = $lastResult->getRemainder();
    }

    /**
     * Bond parsed ok
     * Setup last bond to parsed bond, add index of current node to stack,
     * set SMILES to last parsed remainder and go to next cycle of while
     * @param ParseResult $result
     * @param ParseResult $lastResult
     */
    private function bondAfterBracketOk(ParseResult $result, ParseResult $lastResult) {
        $this->lastBond = $result->getResult();
        $this->arNodesBeforeBracket[] = $this->intNodeIndex - 1;
        $this->strSmiles = $result->getRemainder();
    }

    /**
     * Organic subset parsed ok
     * Add node to graph
     * if isn't first literal of input then add bond to previous node
     * try to parse Smiles number
     *  true -> call tryNumberOk
     *  false -> call tryBond
     * @param ParseResult $result
     * @param ParseResult $lastResult
     */
    private function firstOrgOk(ParseResult $result, ParseResult $lastResult) {
        $this->graph->addNode($result->getResult());
        if ($this->intNodeIndex > 0) {
            $this->graph->addBidirectionalBond($this->intNodeIndex - 1, $this->intNodeIndex, $this->lastBond);
        }
        $this->intNodeIndex++;
        $this->parseAndCallBack($result, $this->smilesNumberParser, 'tryNumberOk', 'tryBond');
    }

    /**
     * If stack isn't empty try to parse ')'
     *  true -> call rightBracketOk
     *  false -> call ko
     * elsewhere wrong input
     * @param ParseResult $result
     * @param ParseResult $lastResult
     * @throws RejectException
     */
    private function backFromBracket(ParseResult $result, ParseResult $lastResult) {
        if (!empty($this->arNodesBeforeBracket)) {
            $this->parseAndCallBack(self::accept(), $this->rightBracketParser, 'rightBracketOk', 'ko');
        } else {
            var_dump("reject III");
            throw new RejectException();
        }
    }

    /**
     * Organic subset after ')' parsed ok
     * Add node and bonds to bond before '('
     * Try to parse Smiles number
     *  true -> call tryNumberOk
     *  false -> call tryBond
     * @param ParseResult $result
     * @param ParseResult $lastResult
     */
    private function orgAfterBracketOk(ParseResult $result, ParseResult $lastResult) {
        $intTargetNodeIndex = array_pop($this->arNodesBeforeBracket);
        $this->graph->addNode($result->getResult());
        $this->graph->addBidirectionalBond($intTargetNodeIndex, $this->intNodeIndex, $this->lastBond);
        $this->intNodeIndex++;
        $this->parseAndCallBack($result, $this->smilesNumberParser, 'tryNumberOk', 'tryBond');
    }

    /**
     * KO
     * Wrong input
     * @param ParseResult $result
     * @param ParseResult $lastResult
     * @throws RejectException
     */
    private function ko(ParseResult $result, ParseResult $lastResult) {
        throw new RejectException();
    }

    /**
     * Bond after ')' parsed ok
     * Try to parse Organic subset
     *  true -> call orgAfterBracket
     *  false -> call ko
     * @param ParseResult $result
     * @param ParseResult $lastResult
     */
    private function bondAfterRightBracketOk(ParseResult $result, ParseResult $lastResult) {
        $this->parseAndCallBack($result, $this->orgParser, 'orgAfterBracketOk', 'ko');
    }

    /**
     * ')' parsed ok
     * Try to parse bond
     *  true -> call bondAfterRightBracketOK
     *  false -> call next
     * @param ParseResult $result
     * @param ParseResult $lastResult
     */
    private function rightBracketOk(ParseResult $result, ParseResult $lastResult) {
        $this->intRightBraces++;
        $this->parseAndCallBack($result, $this->bondParser, 'bondAfterRightBracketOk', 'next');
    }

    /**
     * Try to parse input
     *  parsing ok -> call $funcOk()
     *  parsing ko -> call $funcKo()
     * @param ParseResult $lastResult last result of parsed input
     * @param IParser $parser parser to try
     * @param string $funcOk callback to call if parsing success
     * @param string $funcKo callback to call if parsing go wrong
     */
    private function parseAndCallBack(ParseResult $lastResult, IParser $parser, string $funcOk, string $funcKo) {
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
    public static function reject() {
        return new Reject('Not match SMILES');
    }

    /**
     * A little for starting parseAndCallback()
     * @return Accept
     */
    private function accept() {
        return new Accept('', $this->strSmiles);
    }
}