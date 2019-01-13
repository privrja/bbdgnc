<?php

use Bbdgnc\Finder\ChebiFinder;

final class ComputeMassTest extends \PHPUnit\Framework\TestCase {

    public function testComputeMassWithRightData() {
        $chebiFinder = new ChebiFinder();
        $result = $chebiFinder->computeMass('C62H111N11O12');
        $this->assertGreaterThan(1198.841, $result);
        $this->assertLessThan(1204.841, $result);
    }

    public function testComputeMassWithRightData2() {
        $chebiFinder = new ChebiFinder();
        $result = $chebiFinder->computeMass('C5H8Fe2');
        $this->assertEquals(179.9324802568, $result);
    }

    public function testComputeMassWithNull() {
        $chebiFinder = new ChebiFinder();
        $this->expectException(InvalidArgumentException::class);
        $chebiFinder->computeMass(null);
    }

    public function testComputeMassWithEmptyString() {
        $chebiFinder = new ChebiFinder();
        $this->expectException(InvalidArgumentException::class);
        $chebiFinder->computeMass('');
    }

    public function testComputeMassWithWrongData() {
        $chebiFinder = new ChebiFinder();
        $this->expectException(InvalidArgumentException::class);
        $chebiFinder->computeMass('C');
    }

    public function testComputeMassWithWrongData2() {
        $chebiFinder = new ChebiFinder();
        $this->expectException(InvalidArgumentException::class);
        $chebiFinder->computeMass('CO');
    }

    public function testComputeMassWithWrongData3() {
        $chebiFinder = new ChebiFinder();
        $this->expectException(InvalidArgumentException::class);
        $chebiFinder->computeMass('C2O01');
    }

    public function testComputeMassWithWrongData4() {
        $chebiFinder = new ChebiFinder();
        $this->expectException(InvalidArgumentException::class);
        $chebiFinder->computeMass('C15H27Ke5');
    }

    public function testComputeMassWithWrongData5() {
        $chebiFinder = new ChebiFinder();
        $this->expectException(InvalidArgumentException::class);
        $chebiFinder->computeMass('5');
    }

    public function testComputeMassWithWrongData6() {
        $chebiFinder = new ChebiFinder();
        $this->expectException(InvalidArgumentException::class);
        $chebiFinder->computeMass('C21H');
    }

}
