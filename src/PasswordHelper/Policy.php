<?php

namespace PasswordHelper;

class Policy
{
    /**
     * @var int default minimum number of numbers
     */
    const MINIMUM_DIGITS = 1;

    /**
     * @var int default minimum password length
     */
    const MINIMUM_LENGTH = 10;

    /**
     * @var int default minimum number of letters
     */
    const MINIMUM_LETTERS = 1;

    /**
     * @var int default minimum number of lowercase letters
     */
    const MINIMUM_LOWERCASE = 1;

    /**
     * @var int default minimum number of special characters
     */
    const MINIMUM_SPECIAL_CHARS = 1;

    /**
     * @var int default minimum number of uppercase letters
     */
    const MINIMUM_UPPERCASE = 1;

    /**
     * @var int default minimum number of numbers
     */
    protected $minimumDigits;

    /**
     * @var int default minimum password length
     */
    protected $minimumLength;

    /**
     * @var int default minimum number of letters
     */
    protected $minimumLetters;

    /**
     * @var int minimum number of lowercase letters
     */
    protected $minimumLowercase;

    /**
     * @var int minimum number of special characters
     */
    protected $minimumSpecialChars;

    /**
     * @var int minimum number of uppercase letters
     */
    protected $minimumUppercase;

    public function __construct(array $config = [])
    {
        $this->minimumDigits       = abs((int) (isset($config['minimumDigits']) ? $config['minimumDigits'] : self::MINIMUM_DIGITS));
        $this->minimumLowercase    = abs((int) (isset($config['minimumLowercase']) ? $config['minimumLowercase'] : self::MINIMUM_LOWERCASE));
        $this->minimumSpecialChars = abs((int) (isset($config['minimumSpecialChars']) ? $config['minimumSpecialChars'] : self::MINIMUM_SPECIAL_CHARS));
        $this->minimumUppercase    = abs((int) (isset($config['minimumUppercase']) ? $config['minimumUppercase'] : self::MINIMUM_UPPERCASE));

        $minimumLetters            = abs((int) (isset($config['minimumLetters']) ? $config['minimumLetters'] : self::MINIMUM_LETTERS));
        $this->minimumLetters      = max([$minimumLetters, $this->minimumLowercase + $this->minimumUppercase]);

        $minimumLength             = abs((int) (isset($config['minimumLength']) ? $config['minimumLength'] : self::MINIMUM_LENGTH));
        $this->minimumLength       = max([
            $minimumLength,
            $this->minimumLetters + $this->minimumDigits + $this->minimumSpecialChars
        ]);
    }

    /**
     * Returns the minimum number of numbers required in a password
     *
     * @return int
     */
    public function getMinimumDigits()
    {
        return $this->minimumDigits;
    }

    /**
     * Returns the minimum number of characters required in a password
     *
     * @return int
     */
    public function getMinimumLength()
    {
        return $this->minimumLength;
    }

    /**
     * Returns the minimum number of letters required in a password
     *
     * @return int
     */
    public function getMinimumLetters()
    {
        return $this->minimumLetters;
    }

    /**
     * Returns the minimum number of lowercase letters required in a password
     *
     * @return int
     */
    public function getMinimumLowercase()
    {
        return $this->minimumLowercase;
    }

    /**
     * Returns the minimum number of special characters required in a password
     *
     * @return int
     */
    public function getMinimumSpecialChars()
    {
        return $this->minimumSpecialChars;
    }

    /**
     * Returns the minimum number of uppercase letters required in a password
     *
     * @return int
     */
    public function getMinimumUppercase()
    {
        return $this->minimumUppercase;
    }
}
