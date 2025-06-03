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
            ['a1!Aa1!A', true],
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

    public function testMeetsLengthRequirement(): void
    {
        $validator = new Validator(new Policy(minimumLength: 8, maximumLength: 12));
        $reflectionMethod = new \ReflectionMethod(Validator::class, 'meetsLengthRequirement');
        $reflectionMethod->setAccessible(true);

        self::assertFalse($reflectionMethod->invoke($validator, 'short'));
        self::assertTrue($reflectionMethod->invoke($validator, 'validpass'));
        self::assertFalse($reflectionMethod->invoke($validator, 'toolongpassword'));
    }

    public function testMeetsCharacterTypeRequirements(): void
    {
        $validator = new Validator(new Policy(minimumCharacterTypes: 3));
        $reflectionMethod = new \ReflectionMethod(Validator::class, 'meetsCharacterTypeRequirements');
        $reflectionMethod->setAccessible(true);

        self::assertFalse($reflectionMethod->invoke($validator, 'onlyletters'));
        self::assertFalse($reflectionMethod->invoke($validator, 'letters123'));
        self::assertTrue($reflectionMethod->invoke($validator, 'Letters123!'));
    }

    public function testMeetsComplexityRequirements(): void
    {
        $validator = new Validator(new Policy());
        $reflectionMethod = new \ReflectionMethod(Validator::class, 'meetsComplexityRequirements');
        $reflectionMethod->setAccessible(true);

        self::assertFalse($reflectionMethod->invoke($validator, 'aaa123!'));
        self::assertFalse($reflectionMethod->invoke($validator, 'abc123!'));
        self::assertFalse($reflectionMethod->invoke($validator, 'qwer123!'));
        self::assertFalse($reflectionMethod->invoke($validator, 'valid123!'));
    }
}
