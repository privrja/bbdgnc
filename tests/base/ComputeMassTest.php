<?php

namespace Bbdgnc\Test\Base;

use Bbdgnc\Base\FormulaHelper;
use Bbdgnc\Exception\IllegalArgumentException;
use PHPUnit\Framework\TestCase;

final class ComputeMassTest extends TestCase {

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
        $this->expectException(IllegalArgumentException::class);
        FormulaHelper::computeMass(null);
    }

    public function testComputeMassWithEmptyString() {
        $this->expectException(IllegalArgumentException::class);
        FormulaHelper::computeMass('');
    }

    public function testComputeMassWithWrongData() {
        $this->expectException(IllegalArgumentException::class);
        FormulaHelper::computeMass('C');
    }

    public function testComputeMassWithWrongData2() {
        $this->expectException(IllegalArgumentException::class);
        FormulaHelper::computeMass('CO');
    }

    public function testComputeMassWithWrongData3() {
        $this->expectException(IllegalArgumentException::class);
        FormulaHelper::computeMass('C2O01');
    }

    public function testComputeMassWithWrongData4() {
        $this->expectException(IllegalArgumentException::class);
        FormulaHelper::computeMass('C15H27Ke5');
    }

    public function testComputeMassWithWrongData5() {
        $this->expectException(IllegalArgumentException::class);
        FormulaHelper::computeMass('5');
    }

    public function testComputeMassWithWrongData6() {
        $this->expectException(IllegalArgumentException::class);
        FormulaHelper::computeMass('C21H');
    }

}
