<?php

use PasswordHelper\Policy;
use PHPUnit\Framework\TestCase;

class PolicyTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $policy = new Policy();

        self::assertEquals(Policy::MINIMUM_DIGITS, $policy->getMinimumDigits());
        self::assertEquals(Policy::MINIMUM_LENGTH, $policy->getMinimumLength());
        self::assertEquals(2, $policy->getMinimumLetters());
        self::assertEquals(Policy::MINIMUM_LOWERCASE, $policy->getMinimumLowercase());
        self::assertEquals(Policy::MINIMUM_SPECIAL_CHARS, $policy->getMinimumSpecialChars());
        self::assertEquals(Policy::MINIMUM_UPPERCASE, $policy->getMinimumUppercase());
    }

    public function testDigitsNotRequired(): void
    {
        $policy = new Policy([
            'minimumDigits' => 0
        ]);

        self::assertEquals(0, $policy->getMinimumDigits());
    }

    public function testLowercaseNotRequired(): void
    {
        $policy = new Policy([
            'minimumLowercase' => 0
        ]);

        self::assertEquals(1, $policy->getMinimumLetters());
        self::assertEquals(0, $policy->getMinimumLowercase());
    }

    public function testUppercaseNotRequired(): void
    {
        $policy = new Policy([
            'minimumUppercase' => 0
        ]);

        self::assertEquals(1, $policy->getMinimumLetters());
        self::assertEquals(0, $policy->getMinimumUppercase());
    }

    public function testSpecialCharsNotRequired(): void
    {
        $policy = new Policy([
            'minimumSpecialChars' => 0
        ]);

        self::assertEquals(0, $policy->getMinimumSpecialChars());
    }

    public function dataProviderForMinLetters(): array
    {
        return [
            [0, 0, 0],
            [1, 0, 1],
            [0, 1, 1],
            [1, 1, 2],
        ];
    }

    /**
     * @dataProvider dataProviderForMinLetters
     *
     * @param $lower
     * @param $upper
     * @param $total
     */
    public function testMinimumLettersNeverLessThanTotalLetters(int $lower, int $upper, int $total): void
    {
        $policy = new Policy([
            'minimumLetters' => 0,
            'minimumLowercase' => $upper,
            'minimumUppercase' => $lower,
        ]);

        self::assertEquals($total, $policy->getMinimumLetters());
    }

    public function dataProviderForMinLength(): array
    {
        return [
            [0, 0, 0, 0, 0, Policy::MINIMUM_LENGTH],
            [1, 1, 1, 1, 1, Policy::MINIMUM_LENGTH],
            [2, 2, 2, 2, 2, Policy::MINIMUM_LENGTH],
            [1, 2, 3, 2, 3, Policy::MINIMUM_LENGTH],
            [2, 3, 3, 3, 3, 12],
            [6, 1, 1, 3, 3, 12],
        ];
    }

    /**
     * @dataProvider dataProviderForMinLength
     *
     * @param int $letters
     * @param int $lower
     * @param int $upper
     * @param int $digits
     * @param int $special
     * @param int $total
     */
    public function testMinimumLengthNeverLessThanTotalChars(int $letters, int $lower, int $upper, int $digits, int $special, int $total): void
    {
        $policy = new Policy([
            'minimumLetters' => $letters,
            'minimumDigits' => $digits,
            'minimumLowercase' => $upper,
            'minimumUppercase' => $lower,
            'minimumSpecialChars' => $special
        ]);

        self::assertEquals($total, $policy->getMinimumLength());
    }

    public function testNegativeValues(): void
    {
        $policy = new Policy([
            'minimumLetters' => -5,
            'minimumLength' => -12,
            'minimumDigits' => -2,
            'minimumLowercase' => -2,
            'minimumUppercase' => -2,
            'minimumSpecialChars' => -2
        ]);

        self::assertEquals(2, $policy->getMinimumDigits());
        self::assertEquals(12, $policy->getMinimumLength());
        self::assertEquals(5, $policy->getMinimumLetters());
        self::assertEquals(2, $policy->getMinimumLowercase());
        self::assertEquals(2, $policy->getMinimumSpecialChars());
        self::assertEquals(2, $policy->getMinimumUppercase());
    }

    public function testFloatValues(): void
    {
        $policy = new Policy([
            'minimumLetters' => 5.8,
            'minimumLength' => 12.7,
            'minimumDigits' => 2.6,
            'minimumLowercase' => 2.5,
            'minimumUppercase' => 2.4,
            'minimumSpecialChars' => 2.3
        ]);

        self::assertEquals(2, $policy->getMinimumDigits());
        self::assertEquals(12, $policy->getMinimumLength());
        self::assertEquals(5, $policy->getMinimumLetters());
        self::assertEquals(2, $policy->getMinimumLowercase());
        self::assertEquals(2, $policy->getMinimumSpecialChars());
        self::assertEquals(2, $policy->getMinimumUppercase());
    }
}
