<?php

use PasswordHelper\Policy;
use PasswordHelper\Validator;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
    public function dataProvider(): array
    {
        return [
            ['', false],
            ['a', false],
            ['1', false],
            ['!', false],
            ['Z', false],
            ['a1', false],
            ['a!', false],
            ['aA', false],
            ['a1!', false],
            ['a!A', false],
            ['1!A', false],
            ['a1!Az', false],
            ['a1!Aa1!A', false],
            ['a1!Aa1!Azz', true],
        ];
    }

    /**
     * @dataProvider dataProvider
     * @param string $password
     * @param bool $valid
     */
    public function testIsValidPassword(string $password, bool $valid): void
    {
        $validator = new Validator(new Policy());

        self::assertEquals($valid, $validator->isValidPassword($password));
    }

    public function testMeetsMinimumDigitsIsZero(): void
    {
        $class = new Validator(new Policy(['minimumDigits' => 0]));
        $reflectionMethod = new \ReflectionMethod(Validator::class, 'meetsMinimumDigits');
        $reflectionMethod->setAccessible(true);
        self::assertTrue($reflectionMethod->invoke($class, 'azAZ!@'));
    }

    public function testMeetsMinimumLettersIsZero(): void
    {
        $class = new Validator(new Policy([
            'minimumLetters' => 0,
            'minimumLowercase' => 0,
            'minimumUppercase' => 0,
        ]));
        $reflectionMethod = new \ReflectionMethod(Validator::class, 'meetsMinimumLetters');
        $reflectionMethod->setAccessible(true);
        self::assertTrue($reflectionMethod->invoke($class, '123!@'));
    }

    public function testMeetsMinimumLowercaseIsZero(): void
    {
        $class = new Validator(new Policy(['minimumLowercase' => 0]));
        $reflectionMethod = new \ReflectionMethod(Validator::class, 'meetsMinimumLowercase');
        $reflectionMethod->setAccessible(true);
        self::assertTrue($reflectionMethod->invoke($class, 'AZ!@'));
    }

    public function testMeetsMinimumUppercaseIsZero(): void
    {
        $class = new Validator(new Policy(['minimumUppercase' => 0]));
        $reflectionMethod = new \ReflectionMethod(Validator::class, 'meetsMinimumUppercase');
        $reflectionMethod->setAccessible(true);
        self::assertTrue($reflectionMethod->invoke($class, 'az!@'));
    }

    public function testMeetsMinimumSpecialCharsIsZero(): void
    {
        $class = new Validator(new Policy(['minimumSpecialChars' => 0]));
        $reflectionMethod = new \ReflectionMethod(Validator::class, 'meetsMinimumSpecialChars');
        $reflectionMethod->setAccessible(true);
        self::assertTrue($reflectionMethod->invoke($class, 'azAZ123'));
    }
}
