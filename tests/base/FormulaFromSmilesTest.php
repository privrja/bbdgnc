<?php

namespace Bbdgnc\Test\Base;

use Bbdgnc\Base\FormulaHelper;
use Bbdgnc\Exception\IllegalArgumentException;
use PHPUnit\Framework\TestCase;

final class FormulaFromSmilesTest extends TestCase {

    public function testWithNull() {
        $this->expectException(\TypeError::class);
        FormulaHelper::formulaFromSmiles(null);
    }

    public function testWithEmptyString() {
        $this->assertEquals('', FormulaHelper::formulaFromSmiles(''));
    }

    public function testWithRightData() {
        $this->assertEquals('C62H111N11O12', FormulaHelper::formulaFromSmiles('CCC1C(=O)N(CC(=O)N(C(C(=O)NC(C(=O)N(C(C(=O)NC(C(=O)NC(C(=O)N(C(C(=O)N(C(C(=O)N(C(C(=O)N(C(C(=O)N1)C(C(C)CC=CC)O)C)C(C)C)C)CC(C)C)C)CC(C)C)C)C)C)CC(C)C)C)C(C)C)CC(C)C)C)C'));
    }

    public function testWithRightData2() {
        $this->assertEquals('C61H109N11O12', FormulaHelper::formulaFromSmiles('CC=CCC(C)C(C1C(=O)NC(C(=O)N(CC(=O)N(C(C(=O)NC(C(=O)N(C(C(=O)NC(C(=O)NC(C(=O)N(C(C(=O)N(C(C(=O)N(C(C(=O)N1C)C(C)C)C)CC(C)C)C)CC(C)C)C)C)C)CC(C)C)C)C(C)C)CC(C)C)C)C)C)O'));
    }

    public function testWithRightData3() {
        $this->assertEquals('C8H10N4O2', FormulaHelper::formulaFromSmiles('CN1C=NC2=C1C(=O)N(C(=O)N2C)C'));
    }

    public function testWithRightData4() {
        $this->assertEquals('C31H53N5O7', FormulaHelper::formulaFromSmiles('CCC(C)C1NC(=O)C2C(C)CCN2(C(=O)C(CC(C)C)OC(=O)CCNC(=O)C(C)N(C)C(=O)C(C(C)C)N(C)C1(=O))'));
    }

    public function testWithRightData5() {
        $this->assertEquals('C39H61N7O7', FormulaHelper::formulaFromSmiles('CCC(C)C(NC(C)=O)C(=O)NC2CCCNC(=O)C(NC(=O)C(NC(=O)C3CCCN3(C(=O)C(Cc1ccccc1)NC2(=O)))C(C)CC)C(C)CC'));
    }

    public function testWithWrong() {
        $this->expectException(IllegalArgumentException::class);
        FormulaHelper::formulaFromSmiles('====');
    }

}