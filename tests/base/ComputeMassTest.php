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

    public function testComputeMassWithRightData4() {
        $result = FormulaHelper::computeMass('C10H19NO3');
        $this->assertEquals(201.1364934814, $result);
    }

    public function testComputeMassWithRightData5() {
        $result = FormulaHelper::computeMass('HNO-1');
        $this->assertEquals(-0.9840155848, $result);
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
        $result = FormulaHelper::computeMass('C');
        $this->assertEquals(12, $result);
    }

    public function testComputeMassWithWrongData2() {
        $result = FormulaHelper::computeMass('CO');
        $this->assertEquals(27.9949146221, $result);
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
        $result = FormulaHelper::computeMass('C21H');
        $this->assertEquals(253.0078250321, $result);
    }

}
