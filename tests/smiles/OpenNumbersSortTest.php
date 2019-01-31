<?php

namespace Bbdgnc\Test\Smiles;

use Bbdgnc\Exception\IllegalStateException;
use Bbdgnc\Smiles\OpenNumbersSort;
use PHPUnit\Framework\TestCase;

final class OpenNumbersSortTest extends TestCase {

    public function testCounter() {
        $structure = new OpenNumbersSort();
        for ($index = 0; $index < 5; ++$index) {
            $structure->addOpenNode();
        }
        $structure->addDigit(2);
        $structure->addOpenNode();
        $structure->addDigit(1);
        $structure->addOpenNode();
        $structure->addDigit(6);
        $structure->addDigit(3);
        $expected = [0, 1, 2, 3, 3, 3, 4, 4, 4, 4, 4];
        $actual = [];
        foreach ($structure->getNodes() as $node) {
            $actual[] = $node->getCounter();
        }
        $this->assertEquals($expected, $actual);
    }

    public function testPairs() {
        $structure = new OpenNumbersSort();
        for ($index = 0; $index < 5; ++$index) {
            $structure->addOpenNode();
        }
        $structure->addDigit(2);
        $structure->addOpenNode();
        $structure->addDigit(1);
        $structure->addOpenNode();
        $structure->addDigit(6);
        $structure->addDigit(3);

        $this->assertEquals(false, $structure->getNodes()[0]->isInPair());
        $this->assertEquals(true, $structure->getNodes()[1]->isInPair());
        $this->assertEquals(true, $structure->getNodes()[2]->isInPair());
        $this->assertEquals(true, $structure->getNodes()[3]->isInPair());
        $this->assertEquals(false, $structure->getNodes()[4]->isInPair());
        $this->assertEquals(true, $structure->getNodes()[5]->isInPair());
        $this->assertEquals(true, $structure->getNodes()[6]->isInPair());
        $this->assertEquals(true, $structure->getNodes()[7]->isInPair());
        $this->assertEquals(false, $structure->getNodes()[8]->isInPair());
        $this->assertEquals(true, $structure->getNodes()[9]->isInPair());
        $this->assertEquals(true, $structure->getNodes()[10]->isInPair());
    }

    public function testNumbers() {
        $structure = new OpenNumbersSort();
        for ($index = 0; $index < 5; ++$index) {
            $structure->addOpenNode();
        }
        $structure->addDigit(2);
        $structure->addOpenNode();
        $structure->addDigit(1);
        $structure->addOpenNode();
        $structure->addDigit(6);
        $structure->addDigit(3);
        $this->assertEquals(1, $structure->getNodes()[1]->getNumber());
        $this->assertEquals(2, $structure->getNodes()[2]->getNumber());
        $this->assertEquals(3, $structure->getNodes()[3]->getNumber());
        $this->assertEquals(2, $structure->getNodes()[5]->getNumber());
        $this->assertEquals(4, $structure->getNodes()[6]->getNumber());
        $this->assertEquals(1, $structure->getNodes()[7]->getNumber());
        $this->assertEquals(4, $structure->getNodes()[9]->getNumber());
        $this->assertEquals(3, $structure->getNodes()[10]->getNumber());
    }

    public function testException() {
        $structure = new OpenNumbersSort();
        for ($index = 0; $index < 5; ++$index) {
            $structure->addOpenNode();
        }
        $structure->addDigit(2);
        $structure->addOpenNode();
        $structure->addDigit(1);
        $structure->addOpenNode();
        $structure->addDigit(6);
        $structure->addDigit(3);
        $this->expectException(IllegalStateException::class);
        $structure->getNodes()[0]->getNumber();
    }
}
