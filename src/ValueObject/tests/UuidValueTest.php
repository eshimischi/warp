<?php

declare(strict_types=1);

namespace spaceonfire\ValueObject;

use PHPUnit\Framework\TestCase;

class UuidValueTest extends TestCase
{
    private function factory($val): UuidValue
    {
        return new class ($val) extends UuidValue {
        };
    }

    public function testConstructor(): void
    {
        $val = $this->factory('42779f6d-e7c7-4572-8497-2d43bd9c1677');
        $this->assertEquals('42779f6d-e7c7-4572-8497-2d43bd9c1677', $val->value());
        $this->assertEquals('42779f6d-e7c7-4572-8497-2d43bd9c1677', (string)$val);
        $this->assertEquals('"42779f6d-e7c7-4572-8497-2d43bd9c1677"', json_encode($val));
    }

    public function testConstructFromOtherUuidValueObject(): void
    {
        $valA = $this->factory('42779f6d-e7c7-4572-8497-2d43bd9c1677');
        $valB = $this->factory($valA);
        $this->assertEquals($valA->value(), $valB->value());
    }

    public function testConstructFailWithObject(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->factory(new \stdClass());
    }

    public function testConstructorException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->factory('just string');
    }

    public function testRandom(): void
    {
        $val = $this->factory('42779f6d-e7c7-4572-8497-2d43bd9c1677')::random();
        $this->assertIsString($val->value());
    }
}
