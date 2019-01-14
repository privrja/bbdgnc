<?php

namespace Bbdgnc\Test\Base;

use Bbdgnc\Base\FormulaHelper;

final class ComputeMassTest extends \PHPUnit\Framework\TestCase {

    public function testComputeMassWithRightData() {
        $result = FormulaHelper::computeMass('C62H111N11O12');
        $this->assertGreaterThan(1198.841, $result);
        $this->assertLessThan(1204.841, $result);
    }

    public function testComputeMassWithRightData2() {
        $result = FormulaHelper::computeMass('C5H8Fe2');
        $this->assertEquals(179.9324802568, $result);
    }

    public function testComputeMassWithNull() {
        $this->expectException(InvalidArgumentException::class);
        FormulaHelper::computeMass(null);
    }

    public function testComputeMassWithEmptyString() {
        $this->expectException(InvalidArgumentException::class);
        FormulaHelper::computeMass('');
    }

    public function testComputeMassWithWrongData() {
        $this->expectException(InvalidArgumentException::class);
        FormulaHelper::computeMass('C');
    }

    public function testComputeMassWithWrongData2() {
        $this->expectException(InvalidArgumentException::class);
        FormulaHelper::computeMass('CO');
    }

    public function testComputeMassWithWrongData3() {
        $this->expectException(InvalidArgumentException::class);
        FormulaHelper::computeMass('C2O01');
    }

    public function testComputeMassWithWrongData4() {
        $this->expectException(InvalidArgumentException::class);
        FormulaHelper::computeMass('C15H27Ke5');
    }

    public function testComputeMassWithWrongData5() {
        $this->expectException(InvalidArgumentException::class);
        FormulaHelper::computeMass('5');
    }

    public function testComputeMassWithWrongData6() {
        $this->expectException(InvalidArgumentException::class);
        FormulaHelper::computeMass('C21H');
    }

}
