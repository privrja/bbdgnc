<?php

namespace Bbdgnc\Test\Base;

use Bbdgnc\Base\SequenceHelper;
use Bbdgnc\Exception\IllegalArgumentException;
use PHPUnit\Framework\TestCase;

class SequenceHelperTest extends TestCase {

    public function testWithNull() {
        $this->expectException(\TypeError::class);
        SequenceHelper::getBlockAcronyms(null);
    }

    public function testWithEmptyString() {
        $this->assertEquals([], SequenceHelper::getBlockAcronyms(''));
    }

    public function testWithRightData() {
        $this->assertEquals(['Kok', 'Ple', 'Sau', 'Dor', 'Zim'], SequenceHelper::getBlockAcronyms('[Kok]-[Ple]\([Sau]-[Dor]\)[Zim]'));
    }

    public function testWithRightData2() {
        $this->assertEquals(['Kok', 'Ple-Pod', 'Sau', 'Dor', 'Zim'], SequenceHelper::getBlockAcronyms('[Kok]-[Ple-Pod]\([Sau]-[Dor]\)[Zim]'));
    }

    public function testWithWrongData() {
        $this->expectException(IllegalArgumentException::class);
        SequenceHelper::getBlockAcronyms('[Kok]-[Plo-[Ple-Pod]\([Sau]-[Dor]\)[Zim]');
    }

    public function testWithWrongData2() {
        $this->expectException(IllegalArgumentException::class);
        SequenceHelper::getBlockAcronyms('[Kok]-Plo]-[Ple-Pod]\([Sau]-[Dor]\)[Zim]');
    }

    public function testWithWrongData3() {
        $this->assertEquals(['Kok', 'Plo', 'Ple-Pod', 'Sau', 'Dor', 'Zim'], SequenceHelper::getBlockAcronyms('[Kok]-[Plo]-[Ple-Pod]\([Sau]\(-[Dor]\)[Zim]\)'));
    }
}
