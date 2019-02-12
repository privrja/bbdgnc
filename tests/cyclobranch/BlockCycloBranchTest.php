<?php

namespace Bbdgnc\Test\CycloBranch\Parser;

use Bbdgnc\CycloBranch\BlockCycloBranch;
use Bbdgnc\Enum\ComputeEnum;
use Bbdgnc\Finder\Enum\ServerEnum;
use Bbdgnc\Smiles\Parser\ReferenceParser;
use Bbdgnc\TransportObjects\BlockTO;
use Bbdgnc\TransportObjects\ReferenceTO;

final class BlockCycloBranchTest extends TestCase
{

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

    public function testWithRightData2() {
        $parser = new BlockCycloBranch(null);
        $result = $parser->parseLine("DL-Alanine/D-Alanine/beta-Alanine/N-Methyl-Glycine	Ala/D-Ala/bAla/NMe-Gly	C3H5NO	71.0371137878	CSID: 582/CSID: 64234/CSID: 234/CSID: 1057");
        $arExpected = [];
        $arExpected[] = new BlockTO(0, "DL-Alanine", "Ala", "", ComputeEnum::NO);
        $arExpected[] = new BlockTO(0, "D-Alanine", "D-Ala", "", ComputeEnum::NO);
        $arExpected[] = new BlockTO(0, "beta-Alanine", "bAla", "", ComputeEnum::NO);
        $arExpected[] = new BlockTO(0, "N-Methyl-Glycine", "NMe-Gly", "", ComputeEnum::NO);
        for ($index = 0; $index < 4; ++$index) {
            $arExpected[$index]->mass = 71.0371137878;
            $arExpected[$index]->formula = "C3H5NO";
            $arExpected[$index]->reference = new ReferenceTO();
            $arExpected[$index]->reference->server = ServerEnum::CHEMSPIDER;
        }
        $arExpected[0]->reference->identifier = 582;
        $arExpected[1]->reference->identifier = 64234;
        $arExpected[2]->reference->identifier = 234;
        $arExpected[3]->reference->identifier = 1057;
        $this->assert($arExpected, $result);
    }

    public function testWithWrongData() {
        $parser = new BlockCycloBranch(null);
        $result = $parser->parseLine("Phenylalanine Phe C9H9NO 147.0684140000 CSID: 969");
    }

    public function testWithWrongData2() {
        $parser = new BlockCycloBranch(null);
        $result = $parser->parseLine("Phenylalanine     C9H9NO  147.0684140000  CSID: 969");
    }

}
