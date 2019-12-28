<?php

namespace Tests\Unit\App\Rules;

use PHPUnit\Framework\TestCase;

class PhoneNumberTest extends TestCase
{
    /** @test */
    public function phone_number_can_be_validated()
    {
        $rule = new \App\Rules\PhoneNumber;

        $this->assertFalse($rule->passes('phone_number', '1'));
        $this->assertFalse($rule->passes('phone_number', 'a1#'));
        $this->assertFalse($rule->passes('phone_number', 'aB1#'));
        $this->assertFalse($rule->passes('phone_number', '123456'));
        $this->assertFalse($rule->passes('phone_number', 'aB1234567890123456'));
        $this->assertFalse($rule->passes('phone_number', '123456789'));
        $this->assertTrue($rule->passes('phone_number', '(123) 456-7899'));
        $this->assertTrue($rule->passes('phone_number', '123-456-7899'));
        $this->assertTrue($rule->passes('phone_number', '123.456.7899'));
    }
}
