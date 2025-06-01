<?php

declare(strict_types=1);

namespace PasswordHelper;

/**
 * Defines and manages password policy requirements.
 * 
 * This class handles the configuration and validation of password requirements,
 * including minimum lengths and character type requirements. It provides default
 * values that follow security best practices but can be customized through
 * configuration.
 * 
 * @package PasswordHelper
 */
class Policy
{
    /**
     * Default minimum number of digits required in a password.
     *
     * @var int
     */
    public const MINIMUM_DIGITS = 1;

    /**
     * Default minimum password length.
     *
     * @var int
     */
    public const MINIMUM_LENGTH = 10;

    /**
     * Default minimum number of letters required in a password.
     *
     * @var int
     */
    public const MINIMUM_LETTERS = 1;

    /**
     * Default minimum number of lowercase letters required in a password.
     *
     * @var int
     */
    public const MINIMUM_LOWERCASE = 1;

    /**
     * Default minimum number of special characters required in a password.
     *
     * @var int
     */
    public const MINIMUM_SPECIAL_CHARS = 1;

    /**
     * Default minimum number of uppercase letters required in a password.
     *
     * @var int
     */
    public const MINIMUM_UPPERCASE = 1;

    /**
     * Minimum number of digits required in a password.
     *
     * @var int
     */
    protected $minimumDigits;

    /**
     * Minimum password length required.
     *
     * @var int
     */
    protected $minimumLength;

    /**
     * Minimum number of letters required in a password.
     *
     * @var int
     */
    protected $minimumLetters;

    /**
     * Minimum number of lowercase letters required in a password.
     *
     * @var int
     */
    protected $minimumLowercase;

    /**
     * Minimum number of special characters required in a password.
     *
     * @var int
     */
    protected $minimumSpecialChars;

    /**
     * Minimum number of uppercase letters required in a password.
     *
     * @var int
     */
    protected $minimumUppercase;

    /**
     * Creates a new password policy with optional custom configuration.
     * 
     * If no configuration is provided, the policy will use the default values
     * defined in the class constants. All values are converted to positive integers
     * and validated to ensure they make logical sense (e.g., minimum length must
     * be at least the sum of all character type minimums).
     *
     * @param array<string, int> $config Optional configuration to override defaults:
     *                                   - minimumDigits: Minimum number of digits
     *                                   - minimumLowercase: Minimum number of lowercase letters
     *                                   - minimumSpecialChars: Minimum number of special characters
     *                                   - minimumUppercase: Minimum number of uppercase letters
     *                                   - minimumLetters: Minimum number of total letters
     *                                   - minimumLength: Minimum password length
     */
    public function __construct(array $config = [])
    {
        $this->minimumDigits       = abs((int) ($config['minimumDigits']       ?? self::MINIMUM_DIGITS       ));
        $this->minimumLowercase    = abs((int) ($config['minimumLowercase']    ?? self::MINIMUM_LOWERCASE    ));
        $this->minimumSpecialChars = abs((int) ($config['minimumSpecialChars'] ?? self::MINIMUM_SPECIAL_CHARS));
        $this->minimumUppercase    = abs((int) ($config['minimumUppercase']    ?? self::MINIMUM_UPPERCASE    ));

        $minimumLetters            = abs((int) ($config['minimumLetters']      ?? self::MINIMUM_LETTERS      ));
        $this->minimumLetters      = max([$minimumLetters, $this->minimumLowercase + $this->minimumUppercase]);

        $minimumLength             = abs((int) ($config['minimumLength']       ?? self::MINIMUM_LENGTH       ));
        $this->minimumLength       = max([
            $minimumLength,
            $this->minimumLetters + $this->minimumDigits + $this->minimumSpecialChars
        ]);
    }

    /**
     * Gets the minimum number of digits required in a password.
     *
     * @return int Minimum number of digits required
     */
    public function getMinimumDigits(): int
    {
        return $this->minimumDigits;
    }

    /**
     * Gets the minimum password length required.
     *
     * @return int Minimum password length required
     */
    public function getMinimumLength(): int
    {
        return $this->minimumLength;
    }

    /**
     * Gets the minimum number of letters required in a password.
     *
     * @return int Minimum number of letters required
     */
    public function getMinimumLetters(): int
    {
        return $this->minimumLetters;
    }

    /**
     * Gets the minimum number of lowercase letters required in a password.
     *
     * @return int Minimum number of lowercase letters required
     */
    public function getMinimumLowercase(): int
    {
        return $this->minimumLowercase;
    }

    /**
     * Gets the minimum number of special characters required in a password.
     *
     * @return int Minimum number of special characters required
     */
    public function getMinimumSpecialChars(): int
    {
        return $this->minimumSpecialChars;
    }

    /**
     * Gets the minimum number of uppercase letters required in a password.
     *
     * @return int Minimum number of uppercase letters required
     */
    public function getMinimumUppercase(): int
    {
        return $this->minimumUppercase;
    }
}
