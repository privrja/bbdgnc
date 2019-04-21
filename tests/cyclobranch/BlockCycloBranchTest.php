<?php

namespace Bbdgnc\Test\CycloBranch\Parser;

use Bbdgnc\CycloBranch\BlockCycloBranch;
use Bbdgnc\Enum\ComputeEnum;
use Bbdgnc\Finder\Enum\ServerEnum;
use Bbdgnc\TransportObjects\BlockTO;
use PHPUnit\Framework\TestCase;

final class BlockCycloBranchTest extends TestCase {

//    protected function setUp() {
//        Logger::setPrefix('../.');
//    }
//
//    protected function tearDown() {
//        Logger::clearPrefix();
//    }

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
        $result = $parser->parse("Phenylalanine\tPhe\tC9H9NO\t147.0684140000\t\tCSID: 969");
        $blockTO = new BlockTO(0, "Phenylalanine", "Phe", "", ComputeEnum::NO);
        $blockTO->mass = 147.0684140000;
        $blockTO->formula = "C9H9NO";
        $blockTO->database = ServerEnum::CHEMSPIDER;
        $blockTO->identifier = 969;
        $this->assertEquals([$blockTO->asEntity()], $result->getResult());
    }

    public function testWithRightData2() {
        $parser = new BlockCycloBranch(null);
        $result = $parser->parse("DL-Alanine/D-Alanine/beta-Alanine/N-Methyl-Glycine	Ala/D-Ala/bAla/NMe-Gly\tC3H5NO\t71.0371137878\t\tCSID: 582/CSID: 64234/CSID: 234/CSID: 1057");
        $arExpected = [];
        $arExpected[] = new BlockTO(0, "DL-Alanine", "Ala", "", ComputeEnum::NO);
        $arExpected[] = new BlockTO(0, "D-Alanine", "D-Ala", "", ComputeEnum::NO);
        $arExpected[] = new BlockTO(0, "beta-Alanine", "bAla", "", ComputeEnum::NO);
        $arExpected[] = new BlockTO(0, "N-Methyl-Glycine", "NMe-Gly", "", ComputeEnum::NO);
        for ($index = 0; $index < 4; ++$index) {
            $arExpected[$index]->mass = 71.0371137878;
            $arExpected[$index]->formula = "C3H5NO";
            $arExpected[$index]->database = ServerEnum::CHEMSPIDER;
        }
        $arExpected[0]->identifier = 582;
        $arExpected[1]->identifier = 64234;
        $arExpected[2]->identifier = 234;
        $arExpected[3]->identifier = 1057;
        for ($index = 0; $index < 4; ++$index) {
            $arExpected[$index] = $arExpected[$index]->asEntity();
        }
        $this->assertEquals($arExpected, $result->getResult());
    }

    public function testWithRightData3() {
        $parser = new BlockCycloBranch(null);
        $result = $parser->parse("5.5-dimethyl-2-oxo-hexanoic acid\tC6:0-Me(5.5)-oxo(2)\tC8H12O2\t140.0837296294\t\tCID: 21197379");
        $blockTO = new BlockTO(0, "5.5-dimethyl-2-oxo-hexanoic acid", "C6:0-Me(5.5)-oxo(2)", "CC(C)(C)CCC(=O)C(=O)O", ComputeEnum::NO);
        $blockTO->mass = 140.0837296294;
        $blockTO->formula = "C8H12O2";
        $blockTO->uniqueSmiles = "CC(C)(C)CCC(=O)C(O)=O";
        $blockTO->database = ServerEnum::PUBCHEM;
        $blockTO->identifier = 21197379;
        $this->assertEquals([$blockTO->asEntity()], $result->getResult());
    }

    public function testWithRightData4() {
        $parser = new BlockCycloBranch(null);
        $result = $parser->parse("2-methyl-8-noneic acid\tC9:1(8)-Me(2)\tC10H16O\t152.1201151357\t\tCID: 17824924");
        $blockTO = new BlockTO(0, "2-methyl-8-noneic acid", "C9:1(8)-Me(2)", "CC(CCCCCC=C)C(=O)O", ComputeEnum::NO);
        $blockTO->mass = 152.1201151357;
        $blockTO->formula = "C10H16O";
        $blockTO->uniqueSmiles = "CC(CCCCCC=C)C(O)=O";
        $blockTO->database = ServerEnum::PUBCHEM;
        $blockTO->identifier = 17824924;
        $this->assertEquals([$blockTO->asEntity()], $result->getResult());
    }

    public function testWithRightData5() {
        $parser = new BlockCycloBranch(null);
        $result = $parser->parse("Chloro-Isoleucine\tCl-Ile\tC6H10ClNO\t147.0450919483\t\tCSID: 10269389");
        $blockTO = new BlockTO(0, "Chloro-Isoleucine", "Cl-Ile", "", ComputeEnum::NO);
        $blockTO->mass = 147.0450919483;
        $blockTO->formula = "C6H10ClNO";
        $blockTO->database = ServerEnum::CHEMSPIDER;
        $blockTO->identifier = 10269389;
        $this->assertEquals([$blockTO->asEntity()], $result->getResult());
    }

    public function testWithRightData6() {
        $parser = new BlockCycloBranch(null);
        $result = $parser->parse("DL-Glutamic acid/D-Glutamic Acid/beta-methyl-aspartic acid/D-beta-methyl-aspartic acid/beta-methoxy-aspartic acid/O-acetyl-Serine\tGlu/D-Glu/bMe-Asp/D-bMe-Asp/bOMe-Asp/Ac-Ser\tC5H7NO3\t129.0425930962\t\tCSID: 591/PDB: DGL/PDB: 2AS/PDB: ACB/CSID: 92764/CSID: 184");
        $arExpected = [];
        $length = 6;
        $arExpected[] = new BlockTO(0, "DL-Glutamic acid", "Glu", "", ComputeEnum::NO);
        $arExpected[] = new BlockTO(0, "D-Glutamic Acid", "D-Glu", "", ComputeEnum::NO);
        $arExpected[] = new BlockTO(0, "beta-methyl-aspartic acid", "bMe-Asp", "", ComputeEnum::NO);
        $arExpected[] = new BlockTO(0, "D-beta-methyl-aspartic acid", "D-bMe-Asp", "", ComputeEnum::NO);
        $arExpected[] = new BlockTO(0, "beta-methoxy-aspartic acid", "bOMe-Asp", "", ComputeEnum::NO);
        $arExpected[] = new BlockTO(0, "O-acetyl-Serine", "Ac-Ser", "", ComputeEnum::NO);
        for ($index = 0; $index < $length; ++$index) {
            $arExpected[$index]->mass = 129.0425930962;
            $arExpected[$index]->formula = "C5H7NO3";
        }
        $arExpected[0]->database = ServerEnum::CHEMSPIDER;
        $arExpected[0]->identifier = 591;
        $arExpected[1]->database = ServerEnum::PDB;
        $arExpected[1]->identifier = "DGL";
        $arExpected[2]->database = ServerEnum::PDB;
        $arExpected[2]->identifier = "2AS";
        $arExpected[3]->database = ServerEnum::PDB;
        $arExpected[3]->identifier = "ACB";
        $arExpected[4]->database = ServerEnum::CHEMSPIDER;
        $arExpected[4]->identifier = 92764;
        $arExpected[5]->database = ServerEnum::CHEMSPIDER;
        $arExpected[5]->identifier = 184;
        for ($index = 0; $index < $length; ++$index) {
            $arExpected[$index] = $arExpected[$index]->asEntity();
        }
        $this->assertEquals($arExpected, $result->getResult());
    }

    public function testWithRightData7() {
        $parser = new BlockCycloBranch(null);
        $result = $parser->parse("pyoverdin Pa A chromophore\tChrPaA\tC13H11N3O3\t257.0800412350\t\tCSID: 0");
        $blockTO = new BlockTO(0, "pyoverdin Pa A chromophore", "ChrPaA", "", ComputeEnum::NO);
        $blockTO->mass = 257.0800412350;
        $blockTO->formula = "C13H11N3O3";
        $blockTO->database = ServerEnum::PUBCHEM;
        $this->assertEquals([$blockTO->asEntity()], $result->getResult());
    }

    public function testWithRightData8() {
        $parser = new BlockCycloBranch(null);
        $result = $parser->parse("pyoverdin Pa A chromophore\tChrPaA\tC13H11N3O3\t257.0800412350\tNH3;OH\tNOR00124");
        $blockTO = new BlockTO(0, "pyoverdin Pa A chromophore", "ChrPaA", "", ComputeEnum::NO);
        $blockTO->mass = 257.0800412350;
        $blockTO->formula = "C13H11N3O3";
        $blockTO->losses = 'NH3;OH';
        $blockTO->database = ServerEnum::NORINE;
        $blockTO->identifier = 'NOR00124';
        $this->assertEquals([$blockTO->asEntity()], $result->getResult());
    }

    public function testWithRightData9() {
        $parser = new BlockCycloBranch(null);
        $result = $parser->parse("Chloro-Isoleucine\tCl-Ile\tC6H10ClNO\t147.0450919483\t\tSMILES: C(C(O)=O)(N)C(C(C)Cl)C");
        $blockTO = new BlockTO(0, "Chloro-Isoleucine", "Cl-Ile", "C(C(O)=O)(N)C(C(C)Cl)C", ComputeEnum::NO);
        $blockTO->mass = 147.0450919483;
        $blockTO->formula = "C6H10ClNO";
        $blockTO->uniqueSmiles = "CC(Cl)C(C)C(N)C(O)=O";
        $blockTO->database = ServerEnum::PUBCHEM;
        $this->assertEquals([$blockTO->asEntity()], $result->getResult());
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

    public function testWithWrongData3() {
        $parser = new BlockCycloBranch(null);
        $result = $parser->parse("Chloro-Isoleucine\tCl-Ile\tC6H10ClNO\t147.0450919483\t\tC(C(O)=O)(N)C(C(C)Cl)C in CSID: 10269389");
        $this->assertEquals(BlockCycloBranch::reject(), $result);
    }


}
