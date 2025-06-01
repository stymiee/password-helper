<?php

declare(strict_types=1);

namespace PasswordHelper;

/**
 * Validates passwords against defined policy requirements.
 * 
 * This class provides methods to check if a password meets various policy
 * requirements such as minimum length, character types, and complexity.
 * Each validation method returns a boolean indicating whether the password
 * meets the specific requirement.
 * 
 * @package PasswordHelper
 */
class Validator
{
    /**
     * Password policy configuration.
     *
     * @var Policy
     */
    protected $policy;

    /**
     * Creates a new password validator with the specified policy.
     *
     * @param Policy $policy The password policy to validate against
     */
    public function __construct(Policy $policy)
    {
        $this->policy = $policy;
    }

    /**
     * Validates that a password meets all policy requirements.
     *
     * @param string $password The password to validate
     * @return bool True if the password meets all policy requirements
     */
    public function isValidPassword(string $password): bool
    {
        $password = trim($password);
        return $this->validateMinimumDigits($password) &&
               $this->validateMinimumLength($password) &&
               $this->validateMinimumLetters($password) &&
               $this->validateMinimumUppercase($password) &&
               $this->validateMinimumLowercase($password) &&
               $this->validateMinimumSpecialChars($password);
    }

    /**
     * Validates that a password meets the minimum length requirement.
     *
     * @param string $password The password to validate
     * @return bool True if the password meets the minimum length requirement
     */
    public function validateMinimumLength(string $password): bool
    {
        return strlen($password) >= $this->policy->getMinimumLength();
    }

    /**
     * Validates that a password contains the minimum required number of digits.
     *
     * @param string $password The password to validate
     * @return bool True if the password contains enough digits
     */
    public function validateMinimumDigits(string $password): bool
    {
        return preg_match_all('/\d/', $password, $matches) >= $this->policy->getMinimumDigits();
    }

    /**
     * Validates that a password contains the minimum required number of letters.
     *
     * @param string $password The password to validate
     * @return bool True if the password contains enough letters
     */
    public function validateMinimumLetters(string $password): bool
    {
        return preg_match_all('/[a-z]/i', $password, $matches) >= $this->policy->getMinimumLetters();
    }

    /**
     * Validates that a password contains the minimum required number of uppercase letters.
     *
     * @param string $password The password to validate
     * @return bool True if the password contains enough uppercase letters
     */
    public function validateMinimumUppercase(string $password): bool
    {
        return preg_match_all('/[A-Z]/', $password, $matches) >= $this->policy->getMinimumUppercase();
    }

    /**
     * Validates that a password contains the minimum required number of lowercase letters.
     *
     * @param string $password The password to validate
     * @return bool True if the password contains enough lowercase letters
     */
    public function validateMinimumLowercase(string $password): bool
    {
        return preg_match_all('/[a-z]/', $password, $matches) >= $this->policy->getMinimumLowercase();
    }

    /**
     * Validates that a password contains the minimum required number of special characters.
     *
     * @param string $password The password to validate
     * @return bool True if the password contains enough special characters
     */
    public function validateMinimumSpecialChars(string $password): bool
    {
        return preg_match_all('/[^a-z\d ]/i', $password, $matches) >= $this->policy->getMinimumSpecialChars();
    }

    /**
     * Checks if a password meets the minimum digits requirement (returns true if minimum is zero).
     *
     * @param string $password
     * @return bool
     */
    protected function meetsMinimumDigits(string $password): bool
    {
        if ($this->policy->getMinimumDigits() === 0) {
            return true;
        }
        return $this->validateMinimumDigits($password);
    }

    /**
     * Checks if a password meets the minimum letters requirement (returns true if minimum is zero).
     *
     * @param string $password
     * @return bool
     */
    protected function meetsMinimumLetters(string $password): bool
    {
        if ($this->policy->getMinimumLetters() === 0) {
            return true;
        }
        return $this->validateMinimumLetters($password);
    }

    /**
     * Checks if a password meets the minimum lowercase requirement (returns true if minimum is zero).
     *
     * @param string $password
     * @return bool
     */
    protected function meetsMinimumLowercase(string $password): bool
    {
        if ($this->policy->getMinimumLowercase() === 0) {
            return true;
        }
        return $this->validateMinimumLowercase($password);
    }

    /**
     * Checks if a password meets the minimum uppercase requirement (returns true if minimum is zero).
     *
     * @param string $password
     * @return bool
     */
    protected function meetsMinimumUppercase(string $password): bool
    {
        if ($this->policy->getMinimumUppercase() === 0) {
            return true;
        }
        return $this->validateMinimumUppercase($password);
    }

    /**
     * Checks if a password meets the minimum special chars requirement (returns true if minimum is zero).
     *
     * @param string $password
     * @return bool
     */
    protected function meetsMinimumSpecialChars(string $password): bool
    {
        if ($this->policy->getMinimumSpecialChars() === 0) {
            return true;
        }
        return $this->validateMinimumSpecialChars($password);
    }
}
