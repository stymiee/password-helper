<?php

use PasswordHelper\Generator;
use PasswordHelper\Policy;
use PasswordHelper\Validator;
use PHPUnit\Framework\TestCase;

class GeneratorTest extends TestCase
{
    public function dataProviderForGeneratePassword()
    {
        return [
            [new Policy(['minimumLowercase' => 0]), true, false, true, true],
            [new Policy(['minimumDigits' => 0]), false, true, true, true],
            [new Policy(['minimumSpecialChars' => 0]), true, true, true, false],
            [new Policy(['minimumUppercase' => 0]), true, true, false, true],
            [new Policy(), true, true, true, true],
        ];
    }

    /**
     * @dataProvider dataProviderForGeneratePassword
     *
     * @param Policy $policy
     * @param bool $digits
     * @param bool $lower
     * @param bool $upper
     * @param bool $special
     */
    public function testGeneratePassword($policy, $digits, $lower, $upper, $special)
    {
        $generator = new Generator($policy, new Validator($policy));
        $password = $generator->generatePassword();

        self::assertEquals($policy->getMinimumLength(), strlen($password));
        self::assertEquals((bool) preg_match_all('/\d/', $password, $m), $digits);
        self::assertEquals((bool) preg_match_all('/[a-z]/', $password, $m), $lower);
        self::assertEquals((bool) preg_match_all('/[A-Z]/', $password, $m), $upper);
        self::assertEquals((bool) preg_match_all('/[^a-z\d ]/i', $password, $m), $special);
    }

    public function dataProviderForAvailableCharacters()
    {
        $digits = range(0, 9);
        $lowercase = range('a', 'z');
        $uppercase = range('A', 'Z');
        $specialChars = str_split('^_~@#$%&-=+{};:<>');
        $all = array_merge($digits, $specialChars, $lowercase, $uppercase);

        return [
            [new Policy(['minimumLowercase' => 0, 'minimumUppercase' => 0, 'minimumSpecialChars' => 0]), $digits],
            [new Policy(['minimumDigits' => 0, 'minimumUppercase' => 0, 'minimumSpecialChars' => 0]), $lowercase],
            [new Policy(['minimumLowercase' => 0, 'minimumDigits' => 0, 'minimumSpecialChars' => 0]), $uppercase],
            [new Policy(['minimumLowercase' => 0, 'minimumUppercase' => 0, 'minimumDigits' => 0]), $specialChars],
            [new Policy(), $all],
        ];
    }

    /**
     * @dataProvider dataProviderForAvailableCharacters
     *
     * @param Policy $policy
     * @param array $chars
     */
    public function testGetAvailableCharacters($policy, $chars)
    {
        $class = new Generator($policy, new Validator($policy));
        $reflectionMethod = new \ReflectionMethod(Generator::class, 'getAvailableCharacters');
        $reflectionMethod->setAccessible(true);
        self::assertEquals($reflectionMethod->invoke($class), $chars);
    }

    public function dataProviderForRandomChars()
    {
        return [
            [range(0, 9)],
            [str_split('^_~@#$%&-=+{};:<>')],
            [range('a', 'z')],
            [range('A', 'Z')],
        ];
    }

    /**
     * @dataProvider dataProviderForRandomChars
     *
     * @param array $chars
     */
    public function testGetRandomCharacter(array $chars)
    {
        $policy = new Policy();
        $class = new Generator($policy, new Validator($policy));
        $reflectionMethod = new \ReflectionMethod(Generator::class, 'getRandomCharacter');
        $reflectionMethod->setAccessible(true);
        self::assertContains($reflectionMethod->invoke($class, $chars), $chars);
    }
}
