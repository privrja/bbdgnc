<?php

namespace Bbdgnc\Test\CycloBranch\Parser;

use Bbdgnc\CycloBranch\BlockCycloBranch;
use Bbdgnc\Enum\ComputeEnum;
use Bbdgnc\Finder\Enum\ServerEnum;
use Bbdgnc\TransportObjects\BlockTO;
use Bbdgnc\TransportObjects\ReferenceTO;
use PHPUnit\Framework\TestCase;

final class BlockCycloBranchTest extends TestCase {

    public function testWithNull() {
        $parser = new BlockCycloBranch(null);
        $this->assertEquals(BlockCycloBranch::reject(), $parser->parse(null));
    }

    public function testWithEmptyString() {
        $parser = new BlockCycloBranch(null);
        $this->assertEquals(BlockCycloBranch::reject(), $parser->parse(""));
    }

    public function testWithRightData() {
        $parser = new BlockCycloBranch(null);
        $result = $parser->parse("Phenylalanine\tPhe\tC9H9NO\t147.0684140000\tCSID: 969");
        $blockTO = new BlockTO(0, "Phenylalanine", "Phe", "", ComputeEnum::NO);
        $blockTO->mass = 147.0684140000;
        $blockTO->formula = "C9H9NO";
        $blockTO->reference = new ReferenceTO();
        $blockTO->reference->server = ServerEnum::CHEMSPIDER;
        $blockTO->reference->identifier = 969;
        $this->assertEquals([$blockTO->asBlock()], $result->getResult());
    }

    public function testWithRightData2() {
        $parser = new BlockCycloBranch(null);
        $result = $parser->parse("DL-Alanine/D-Alanine/beta-Alanine/N-Methyl-Glycine	Ala/D-Ala/bAla/NMe-Gly\tC3H5NO\t71.0371137878\tCSID: 582/CSID: 64234/CSID: 234/CSID: 1057");
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
        for ($index = 0; $index < 4; ++$index) {
            $arExpected[$index] = $arExpected[$index]->asBlock();
        }
        $this->assertEquals($arExpected, $result->getResult());
    }

    public function testWithRightData3() {
        $parser = new BlockCycloBranch(null);
        $result = $parser->parse("5.5-dimethyl-2-oxo-hexanoic acid\tC6:0-Me(5.5)-oxo(2)\tC8H12O2\t140.0837296294\tCID: 21197379");
        $blockTO = new BlockTO(0, "5.5-dimethyl-2-oxo-hexanoic acid", "C6:0-Me(5.5)-oxo(2)", "CC(C)(C)CCC(=O)C(=O)O", ComputeEnum::NO);
        $blockTO->mass = 140.0837296294;
        $blockTO->formula = "C8H12O2";
        $blockTO->uniqueSmiles = "CC(C)(C)CCC(=O)C(O)=O";
        $blockTO->reference = new ReferenceTO();
        $blockTO->reference->server = ServerEnum::PUBCHEM;
        $blockTO->reference->identifier = 21197379;
        $this->assertEquals([$blockTO->asBlock()], $result->getResult());
    }

    public function testWithRightData4() {
        $parser = new BlockCycloBranch(null);
        $result = $parser->parse("2-methyl-8-noneic acid\tC9:1(8)-Me(2)\tC10H16O\t152.1201151357\tCID: 17824924");
        $blockTO = new BlockTO(0, "2-methyl-8-noneic acid", "C9:1(8)-Me(2)", "CC(CCCCCC=C)C(=O)O", ComputeEnum::NO);
        $blockTO->mass = 152.1201151357;
        $blockTO->formula = "C10H16O";
        $blockTO->uniqueSmiles = "CC(CCCCCC=C)C(O)=O";
        $blockTO->reference = new ReferenceTO();
        $blockTO->reference->server = ServerEnum::PUBCHEM;
        $blockTO->reference->identifier = 17824924;
        $this->assertEquals([$blockTO->asBlock()], $result->getResult());
    }

    public function testWithWrongData() {
        $parser = new BlockCycloBranch(null);
        $result = $parser->parse("Phenylalanine Phe C9H9NO 147.0684140000 CSID: 969");
        $this->assertEquals(BlockCycloBranch::reject(), $result);
    }

    public function testWithWrongData2() {
        $parser = new BlockCycloBranch(null);
        $result = $parser->parse("Phenylalanine\t\tC9H9NO\t147.0684140000\tCSID: 969");
        $this->assertEquals(BlockCycloBranch::reject(), $result);
    }

}
