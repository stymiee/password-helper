<?php

declare(strict_types=1);

namespace PasswordHelper;

/**
 * Validates passwords against a set of policy requirements.
 * 
 * This class provides methods to check if passwords meet various security
 * requirements such as minimum length, character types, and complexity rules.
 * 
 * @package PasswordHelper
 */
class Validator
{
    /**
     * Creates a new password validator.
     */
    public function __construct(
        private Policy $policy
    ) {
    }

    /**
     * Validates a password against the policy requirements.
     *
     * @param string $password The password to validate
     * @return bool True if the password meets all requirements
     */
    public function isValidPassword(string $password): bool
    {
        return $this->meetsLengthRequirement($password) &&
               $this->meetsCharacterTypeRequirements($password) &&
               $this->meetsComplexityRequirements($password);
    }

    /**
     * Checks if the password meets the length requirements.
     *
     * @param string $password The password to check
     * @return bool True if the length requirements are met
     */
    private function meetsLengthRequirement(string $password): bool
    {
        $length = strlen($password);
        return $length >= $this->policy->getMinimumLength() &&
               $length <= $this->policy->getMaximumLength();
    }

    /**
     * Checks if the password meets the character type requirements.
     *
     * @param string $password The password to check
     * @return bool True if the character type requirements are met
     */
    private function meetsCharacterTypeRequirements(string $password): bool
    {
        return $this->countCharacterTypes($password) >= $this->policy->getMinimumCharacterTypes();
    }

    /**
     * Counts the number of different character types in the password.
     *
     * @param string $password The password to check
     * @return int The number of different character types
     */
    private function countCharacterTypes(string $password): int
    {
        $types = 0;
        
        if (preg_match('/[A-Z]/', $password)) $types++;
        if (preg_match('/[a-z]/', $password)) $types++;
        if (preg_match('/\d/', $password)) $types++;
        if (preg_match('/[^a-zA-Z\d]/', $password)) $types++;
        
        return $types;
    }

    /**
     * Checks if the password meets the complexity requirements.
     *
     * @param string $password The password to check
     * @return bool True if the complexity requirements are met
     */
    private function meetsComplexityRequirements(string $password): bool
    {
        return !$this->hasRepeatedCharacters($password) &&
               !$this->hasSequentialCharacters($password) &&
               !$this->hasCommonPatterns($password);
    }

    /**
     * Checks if the password contains repeated characters.
     *
     * @param string $password The password to check
     * @return bool True if repeated characters are found
     */
    private function hasRepeatedCharacters(string $password): bool
    {
        return (bool) preg_match('/(.)\1{2,}/', $password);
    }

    /**
     * Checks if the password contains sequential characters.
     *
     * @param string $password The password to check
     * @return bool True if sequential characters are found
     */
    private function hasSequentialCharacters(string $password): bool
    {
        return $this->hasSequentialNumbers($password) ||
               $this->hasSequentialLetters($password) ||
               $this->hasKeyboardPattern($password);
    }

    /**
     * Checks if the password contains sequential numbers.
     *
     * @param string $password The password to check
     * @return bool True if sequential numbers are found
     */
    private function hasSequentialNumbers(string $password): bool
    {
        return (bool) preg_match('/012|123|234|345|456|567|678|789/', $password);
    }

    /**
     * Checks if the password contains sequential letters.
     *
     * @param string $password The password to check
     * @return bool True if sequential letters are found
     */
    private function hasSequentialLetters(string $password): bool
    {
        return (bool) preg_match('/abc|bcd|cde|def|efg|fgh|ghi|hij|ijk|jkl|klm|lmn|mno|nop|opq|pqr|qrs|rst|stu|tuv|uvw|vwx|wxy|xyz/i', $password);
    }

    /**
     * Checks if the password contains keyboard patterns.
     *
     * @param string $password The password to check
     * @return bool True if keyboard patterns are found
     */
    private function hasKeyboardPattern(string $password): bool
    {
        return (bool) preg_match('/qwer|wert|erty|rtyu|tyui|yuio|uiop|asdf|sdfg|dfgh|fghj|ghjk|hjkl|zxcv|xcvb|cvbn|vbnm/i', $password);
    }

    /**
     * Checks if the password contains common patterns.
     *
     * @param string $password The password to check
     * @return bool True if common patterns are found
     */
    private function hasCommonPatterns(string $password): bool
    {
        $commonPatterns = [
            '123456', 'password', 'qwerty', 'admin', 'welcome',
            'monkey', 'letmein', 'dragon', 'baseball', 'iloveyou',
            'trustno1', 'sunshine', 'master', 'hello', 'shadow',
            'ashley', 'football', 'jesus', 'michael', 'ninja',
            'mustang', 'password1', '12345678', 'qwerty123', 'admin123'
        ];

        $password = strtolower($password);
        
        foreach ($commonPatterns as $pattern) {
            if (str_contains($password, $pattern)) {
                return true;
            }
        }
        
        return false;
    }
}
