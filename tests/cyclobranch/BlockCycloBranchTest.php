<?php

namespace Bbdgnc\Test\CycloBranch\Parser;

use Bbdgnc\CycloBranch\BlockCycloBranch;
use Bbdgnc\Enum\ComputeEnum;
use Bbdgnc\Finder\Enum\ServerEnum;
use Bbdgnc\TransportObjects\BlockTO;
use Bbdgnc\TransportObjects\ReferenceTO;

final class BlockCycloBranchTest extends TestCase {

    public function testWithNull() {
        $parser = new BlockCycloBranch(null);
        $parser->parseLine(null);
    }

    public function testWithEmptyString() {
        $parser = new BlockCycloBranch(null);
        $parser->parseLine("");
    }

    public function testWithRightData() {
        $parser = new BlockCycloBranch(null);
        $result = $parser->parseLine("Phenylalanine	Phe	C9H9NO	147.0684140000		CSID: 969");
        $blockTO = new BlockTO(0, "Phenylalanine", "Phe", "NC(CC1=CC=CC=C1)C(O)=O", ComputeEnum::NO);
        $blockTO->mass = 147.0684140000;
        $blockTO->formula = "C9H9NO";
        $blockTO->reference = new ReferenceTO();
        $blockTO->reference->server = ServerEnum::CHEMSPIDER;
        $blockTO->reference->identifier = 969;
        $this->assert($blockTO, $result[0]);
    }

    public function testWithWrongData() {
    }

}