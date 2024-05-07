<?php declare(strict_types=1);

namespace Unit;

final class InitialTest extends \PHPUnit\Framework\TestCase
{
    public function testSomething(): void
    {
        $this->assertTrue(true);
    }

    public function testSomethingAndFail(): void
    {
        $this->fail("This test is expected to fail.");
    }
}