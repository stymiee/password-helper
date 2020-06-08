<?php

use PasswordHelper\Policy;
use PHPUnit\Framework\TestCase;

class PolicyTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $policy = new Policy();

        $this->assertEquals(Policy::MINIMUM_DIGITS, $policy->getMinimumDigits());
        $this->assertEquals(Policy::MINIMUM_LENGTH, $policy->getMinimumLength());
        $this->assertEquals(2, $policy->getMinimumLetters());
        $this->assertEquals(Policy::MINIMUM_LOWERCASE, $policy->getMinimumLowercase());
        $this->assertEquals(Policy::MINIMUM_SPECIAL_CHARS, $policy->getMinimumSpecialChars());
        $this->assertEquals(Policy::MINIMUM_UPPERCASE, $policy->getMinimumUppercase());
    }

    public function testDigitsNotRequired(): void
    {
        $policy = new Policy([
            'minimumDigits' => 0
        ]);

        $this->assertEquals(0, $policy->getMinimumDigits());
    }

    public function testLowercaseNotRequired(): void
    {
        $policy = new Policy([
            'minimumLowercase' => 0
        ]);

        $this->assertEquals(1, $policy->getMinimumLetters());
        $this->assertEquals(0, $policy->getMinimumLowercase());
    }

    public function testUppercaseNotRequired(): void
    {
        $policy = new Policy([
            'minimumUppercase' => 0
        ]);

        $this->assertEquals(1, $policy->getMinimumLetters());
        $this->assertEquals(0, $policy->getMinimumUppercase());
    }

    public function testSpecialCharsNotRequired(): void
    {
        $policy = new Policy([
            'minimumSpecialChars' => 0
        ]);

        $this->assertEquals(0, $policy->getMinimumSpecialChars());
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

        $this->assertEquals($total, $policy->getMinimumLetters());
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

        $this->assertEquals($total, $policy->getMinimumLength());
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

        $this->assertEquals(2, $policy->getMinimumDigits());
        $this->assertEquals(12, $policy->getMinimumLength());
        $this->assertEquals(5, $policy->getMinimumLetters());
        $this->assertEquals(2, $policy->getMinimumLowercase());
        $this->assertEquals(2, $policy->getMinimumSpecialChars());
        $this->assertEquals(2, $policy->getMinimumUppercase());
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

        $this->assertEquals(2, $policy->getMinimumDigits());
        $this->assertEquals(12, $policy->getMinimumLength());
        $this->assertEquals(5, $policy->getMinimumLetters());
        $this->assertEquals(2, $policy->getMinimumLowercase());
        $this->assertEquals(2, $policy->getMinimumSpecialChars());
        $this->assertEquals(2, $policy->getMinimumUppercase());
    }
}
