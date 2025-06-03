<?php

declare(strict_types=1);

namespace PasswordHelper;

/**
 * Defines password policy requirements and constraints.
 * 
 * This class encapsulates all the rules and requirements that passwords
 * must meet to be considered valid according to the policy.
 * 
 * @package PasswordHelper
 */
class Policy
{
    /**
     * Creates a new password policy with the specified requirements.
     */
    public function __construct(
        private int $minimumLength = 8,
        private int $maximumLength = 20,
        private int $minimumCharacterTypes = 3,
        private bool $allowRepeatedCharacters = false,
        private bool $allowSequentialCharacters = false,
        private bool $allowCommonPatterns = false
    ) {
        if ($this->minimumLength < 8) {
            throw new \InvalidArgumentException('Minimum length must be at least 8 characters');
        }
        
        if ($this->maximumLength < $this->minimumLength) {
            throw new \InvalidArgumentException('Maximum length must be greater than minimum length');
        }
        
        if ($this->minimumCharacterTypes < 1 || $this->minimumCharacterTypes > 4) {
            throw new \InvalidArgumentException('Minimum character types must be between 1 and 4');
        }
    }

    /**
     * Gets the minimum required password length.
     *
     * @return int The minimum length
     */
    public function getMinimumLength(): int
    {
        return $this->minimumLength;
    }

    /**
     * Gets the maximum allowed password length.
     *
     * @return int The maximum length
     */
    public function getMaximumLength(): int
    {
        return $this->maximumLength;
    }

    /**
     * Gets the minimum required number of character types.
     *
     * @return int The minimum number of character types
     */
    public function getMinimumCharacterTypes(): int
    {
        return $this->minimumCharacterTypes;
    }

    /**
     * Checks if repeated characters are allowed.
     *
     * @return bool True if repeated characters are allowed
     */
    public function areRepeatedCharactersAllowed(): bool
    {
        return $this->allowRepeatedCharacters;
    }

    /**
     * Checks if sequential characters are allowed.
     *
     * @return bool True if sequential characters are allowed
     */
    public function areSequentialCharactersAllowed(): bool
    {
        return $this->allowSequentialCharacters;
    }

    /**
     * Checks if common patterns are allowed.
     *
     * @return bool True if common patterns are allowed
     */
    public function areCommonPatternsAllowed(): bool
    {
        return $this->allowCommonPatterns;
    }
}
