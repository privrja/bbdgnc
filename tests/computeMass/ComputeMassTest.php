<?php


final class ComputeMassTest extends \PHPUnit\Framework\TestCase {

    public function testComputeMass() {
        $chebiFinder = new \Bbdgnc\Finder\ChebiFinder();
        $result = $chebiFinder->computeMass('C62H111N11O12');
        $this->assertGreaterThan(1198.841, $result);
        $this->assertLessThan(1204.841, $result);
    }

}
