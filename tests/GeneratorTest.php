<?php

use PasswordHelper\Generator;
use PHPUnit\Framework\TestCase;

class GeneratorTest extends TestCase
{
    public function testDefaultConstructor(): void
    {
        $generator = new Generator();
        
        self::assertEquals(12, $generator->getMinLength());
        self::assertEquals(20, $generator->getMaxLength());
    }

    public function testCustomConstructor(): void
    {
        $generator = new Generator(8, 16);
        
        self::assertEquals(8, $generator->getMinLength());
        self::assertEquals(16, $generator->getMaxLength());
    }

    public function testGenerateWithAllTypes(): void
    {
        $generator = new Generator();
        $password = $generator->generate();

        self::assertGreaterThanOrEqual(12, strlen($password));
        self::assertLessThanOrEqual(20, strlen($password));
        self::assertRegExp('/[A-Z]/', $password);
        self::assertRegExp('/[a-z]/', $password);
        self::assertRegExp('/\d/', $password);
        self::assertRegExp('/[^a-zA-Z\d]/', $password);
    }

    public function testGenerateWithSpecificTypes(): void
    {
        $generator = new Generator();
        
        // Test with only uppercase and numbers
        $password = $generator->generate(includeUppercase: true, includeLowercase: false, includeNumbers: true, includeSpecial: false);
        self::assertRegExp('/[A-Z]/', $password);
        self::assertNotRegExp('/[a-z]/', $password);
        self::assertRegExp('/\d/', $password);
        self::assertNotRegExp('/[^a-zA-Z\d]/', $password);

        // Test with only lowercase and special
        $password = $generator->generate(includeUppercase: false, includeLowercase: true, includeNumbers: false, includeSpecial: true);
        self::assertNotRegExp('/[A-Z]/', $password);
        self::assertRegExp('/[a-z]/', $password);
        self::assertNotRegExp('/\d/', $password);
        self::assertRegExp('/[^a-zA-Z\d]/', $password);
    }

    public function testGenerateWithNoTypes(): void
    {
        $generator = new Generator();
        
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('At least one character type must be selected');
        
        $generator->generate(includeUppercase: false, includeLowercase: false, includeNumbers: false, includeSpecial: false);
    }

    public function testInvalidMinLength(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Minimum length must be at least 8 characters');
        
        new Generator(7);
    }

    public function testInvalidMaxLength(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Maximum length must be greater than minimum length');
        
        new Generator(10, 9);
    }

    public function dataProviderForRandomChars(): array
    {
        return [
            [array_map('strval', range(0, 9))],
            [str_split('!@#$%^&*()_+-=[]{}|;:,.<>?')],
            [range('a', 'z')],
            [range('A', 'Z')],
        ];
    }

    /**
     * @dataProvider dataProviderForRandomChars
     *
     * @param array $chars
     */
    public function testGetRandomCharacter(array $chars): void
    {
        $generator = new Generator();
        $reflectionMethod = new \ReflectionMethod(Generator::class, 'getRandomCharacter');
        $reflectionMethod->setAccessible(true);
        
        $result = $reflectionMethod->invoke($generator, $chars);
        self::assertIsString($result);
        self::assertContains($result, $chars);
    }

    public function testBuildCharacterPool(): void
    {
        $generator = new Generator();
        $reflectionMethod = new \ReflectionMethod(Generator::class, 'buildCharacterPool');
        $reflectionMethod->setAccessible(true);

        // Test with all character types
        $chars = $reflectionMethod->invoke($generator, true, true, true, true);
        self::assertContains('A', $chars);
        self::assertContains('a', $chars);
        self::assertContains('0', $chars);
        self::assertContains('!', $chars);

        // Test with only uppercase and numbers
        $chars = $reflectionMethod->invoke($generator, true, false, true, false);
        self::assertContains('A', $chars);
        self::assertNotContains('a', $chars);
        self::assertContains('0', $chars);
        self::assertNotContains('!', $chars);
    }

    public function testGenerateRequiredCharacters(): void
    {
        $generator = new Generator();
        $reflectionMethod = new \ReflectionMethod(Generator::class, 'generateRequiredCharacters');
        $reflectionMethod->setAccessible(true);

        // Test with all character types
        $password = $reflectionMethod->invoke($generator, true, true, true, true);
        self::assertEquals(4, strlen($password));
        self::assertRegExp('/[A-Z]/', $password);
        self::assertRegExp('/[a-z]/', $password);
        self::assertRegExp('/\d/', $password);
        self::assertRegExp('/[^a-zA-Z\d]/', $password);

        // Test with only uppercase and numbers
        $password = $reflectionMethod->invoke($generator, true, false, true, false);
        self::assertEquals(2, strlen($password));
        self::assertRegExp('/[A-Z]/', $password);
        self::assertNotRegExp('/[a-z]/', $password);
        self::assertRegExp('/\d/', $password);
        self::assertNotRegExp('/[^a-zA-Z\d]/', $password);
    }

    public function testFillRemainingCharacters(): void
    {
        $generator = new Generator();
        $reflectionMethod = new \ReflectionMethod(Generator::class, 'fillRemainingCharacters');
        $reflectionMethod->setAccessible(true);

        $initialPassword = 'Ab1!';
        $chars = array_merge(range('A', 'Z'), range('a', 'z'), array_map('strval', range(0, 9)), str_split('!@#$%^&*()_+-=[]{}|;:,.<>?'));
        $length = 8;

        $password = $reflectionMethod->invoke($generator, $initialPassword, $chars, $length);
        self::assertEquals($length, strlen($password));
        self::assertStringStartsWith($initialPassword, $password);
    }
}
