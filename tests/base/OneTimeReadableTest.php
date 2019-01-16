<?php

namespace Bbdgnc\Test\Base;

use Bbdgnc\Base\OneTimeReadable;
use Bbdgnc\Exception\ReadOnlyOneTimeException;
use PHPUnit\Framework\TestCase;

final class OneTimeReadableTest extends TestCase {

    public function testWithEmptyString() {
        $memory = new OneTimeReadable('');
        $this->assertEquals(false, $memory->isRead());
        $this->assertEquals('', $memory->getObject());
        $this->expectException(ReadOnlyOneTimeException::class);
        $memory->getObject();
    }
}