<?php

use PasswordHelper\Policy;
use PHPUnit\Framework\TestCase;

class PolicyTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $policy = new Policy();

        self::assertEquals(8, $policy->getMinimumLength());
        self::assertEquals(20, $policy->getMaximumLength());
        self::assertEquals(3, $policy->getMinimumCharacterTypes());
        self::assertFalse($policy->areRepeatedCharactersAllowed());
        self::assertFalse($policy->areSequentialCharactersAllowed());
        self::assertFalse($policy->areCommonPatternsAllowed());
    }

    public function testCustomValues(): void
    {
        $policy = new Policy(
            minimumLength: 12,
            maximumLength: 30,
            minimumCharacterTypes: 4,
            allowRepeatedCharacters: true,
            allowSequentialCharacters: true,
            allowCommonPatterns: true
        );

        self::assertEquals(12, $policy->getMinimumLength());
        self::assertEquals(30, $policy->getMaximumLength());
        self::assertEquals(4, $policy->getMinimumCharacterTypes());
        self::assertTrue($policy->areRepeatedCharactersAllowed());
        self::assertTrue($policy->areSequentialCharactersAllowed());
        self::assertTrue($policy->areCommonPatternsAllowed());
    }

    public function testMinimumLengthValidation(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Minimum length must be at least 8 characters');
        
        new Policy(minimumLength: 7);
    }

    public function testMaximumLengthValidation(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Maximum length must be greater than minimum length');
        
        new Policy(minimumLength: 10, maximumLength: 9);
    }

    public function testMinimumCharacterTypesValidation(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Minimum character types must be between 1 and 4');
        
        new Policy(minimumCharacterTypes: 5);
    }

    public function testMinimumCharacterTypesValidationZero(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Minimum character types must be between 1 and 4');
        
        new Policy(minimumCharacterTypes: 0);
    }
}
