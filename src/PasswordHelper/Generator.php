<?php

declare(strict_types=1);

namespace PasswordHelper;

/**
 * Generates secure random passwords based on specified requirements.
 * 
 * This class provides methods to generate passwords with various character types
 * and ensures they meet minimum security requirements.
 * 
 * @package PasswordHelper
 */
class Generator
{
    /**
     * Default minimum length for generated passwords.
     */
    private const DEFAULT_MIN_LENGTH = 12;

    /**
     * Default maximum length for generated passwords.
     */
    private const DEFAULT_MAX_LENGTH = 20;

    /**
     * Creates a new password generator.
     */
    public function __construct(
        private int $minLength = self::DEFAULT_MIN_LENGTH,
        private int $maxLength = self::DEFAULT_MAX_LENGTH
    ) {
        if ($this->minLength < 8) {
            throw new \InvalidArgumentException('Minimum length must be at least 8 characters');
        }
        
        if ($this->maxLength < $this->minLength) {
            throw new \InvalidArgumentException('Maximum length must be greater than minimum length');
        }
    }

    /**
     * Gets the minimum length for generated passwords.
     *
     * @return int The minimum length
     */
    public function getMinLength(): int
    {
        return $this->minLength;
    }

    /**
     * Gets the maximum length for generated passwords.
     *
     * @return int The maximum length
     */
    public function getMaxLength(): int
    {
        return $this->maxLength;
    }

    /**
     * Generates a random password that meets the specified requirements.
     *
     * @param bool $includeUppercase Whether to include uppercase letters
     * @param bool $includeLowercase Whether to include lowercase letters
     * @param bool $includeNumbers Whether to include numbers
     * @param bool $includeSpecial Whether to include special characters
     * @return string The generated password
     * @throws \InvalidArgumentException If no character types are selected
     */
    public function generate(
        bool $includeUppercase = true,
        bool $includeLowercase = true,
        bool $includeNumbers = true,
        bool $includeSpecial = true
    ): string {
        if (!$includeUppercase && !$includeLowercase && !$includeNumbers && !$includeSpecial) {
            throw new \InvalidArgumentException('At least one character type must be selected');
        }

        $chars = [];
        
        if ($includeUppercase) {
            $chars = array_merge($chars, range('A', 'Z'));
        }
        
        if ($includeLowercase) {
            $chars = array_merge($chars, range('a', 'z'));
        }
        
        if ($includeNumbers) {
            $chars = array_merge($chars, array_map('strval', range(0, 9)));
        }
        
        if ($includeSpecial) {
            $chars = array_merge($chars, str_split('!@#$%^&*()_+-=[]{}|;:,.<>?'));
        }

        $length = random_int($this->minLength, $this->maxLength);
        $password = '';

        // Ensure at least one character from each selected type
        if ($includeUppercase) {
            $password .= $this->getRandomCharacter(range('A', 'Z'));
        }
        
        if ($includeLowercase) {
            $password .= $this->getRandomCharacter(range('a', 'z'));
        }
        
        if ($includeNumbers) {
            $password .= $this->getRandomCharacter(array_map('strval', range(0, 9)));
        }
        
        if ($includeSpecial) {
            $password .= $this->getRandomCharacter(str_split('!@#$%^&*()_+-=[]{}|;:,.<>?'));
        }

        // Fill the rest of the password with random characters
        while (strlen($password) < $length) {
            $password .= $this->getRandomCharacter($chars);
        }

        // Shuffle the password to ensure random distribution
        return str_shuffle($password);
    }

    /**
     * Gets a random character from the given array.
     *
     * @param array<int, string> $chars Array of characters to choose from
     * @return string A random character
     */
    private function getRandomCharacter(array $chars): string
    {
        return (string) $chars[random_int(0, count($chars) - 1)];
    }
}
