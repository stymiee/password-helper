<?php

declare(strict_types=1);

namespace PasswordHelper;

class Validator
{
    /**
     * @var Policy Contains password policy logic
     */
    protected $policy;

    public function __construct(Policy $policy)
    {
        $this->policy = $policy;
    }

    /**
     * Determines if a password satisfies the password policy
     *
     * @param string $password
     *
     * @return bool
     */
    public function isValidPassword(string $password): bool
    {
        $password = trim($password);
        return $this->meetsMinimumDigits($password) &&
               $this->meetsMinimumLength($password) &&
               $this->meetsMinimumLetters($password) &&
               $this->meetsMinimumUppercase($password) &&
               $this->meetsMinimumLowercase($password) &&
               $this->meetsMinimumSpecialChars($password);
    }

    /**
     * Determines if a password meets the minimum password length
     *
     * @param string $password
     *
     * @return bool
     */
    protected function meetsMinimumLength(string $password): bool
    {
        return strlen($password) >= $this->policy->getMinimumLength();
    }

    /**
     * Determines if a password contains the minimum number of digits
     *
     * @param string $password
     *
     * @return bool
     */
    protected function meetsMinimumDigits(string $password): bool
    {
        if ($this->policy->getMinimumDigits() === 0) {
            return true;
        }
        return preg_match_all('/\d/', $password, $matches) >= $this->policy->getMinimumDigits();
    }

    /**
     * Determines if a password contains the minimum number of letters (any case)
     *
     * @param string $password
     *
     * @return bool
     */
    protected function meetsMinimumLetters(string $password): bool
    {
        if ($this->policy->getMinimumLetters() === 0) {
            return true;
        }
        return preg_match_all('/[a-z]/i', $password, $matches) >= $this->policy->getMinimumDigits();
    }

    /**
     * Determines if a password contains the minimum number of uppercase letters
     *
     * @param string $password
     *
     * @return bool
     */
    protected function meetsMinimumUppercase(string $password): bool
    {
        if ($this->policy->getMinimumUppercase() === 0) {
            return true;
        }
        return preg_match_all('/[A-Z]/', $password, $matches) >= $this->policy->getMinimumDigits();
    }

    /**
     * Determines if a password contains the minimum number of lowercase letters
     *
     * @param string $password
     *
     * @return bool
     */
    protected function meetsMinimumLowercase(string $password): bool
    {
        if ($this->policy->getMinimumLowercase() === 0) {
            return true;
        }
        return preg_match_all('/[a-z]/', $password, $matches) >= $this->policy->getMinimumDigits();
    }

    /**
     * Determines if a password contains the minimum number of special characters
     *
     * @param string $password
     *
     * @return bool
     */
    protected function meetsMinimumSpecialChars(string $password): bool
    {
        if ($this->policy->getMinimumSpecialChars() === 0) {
            return true;
        }
        return preg_match_all('/[^a-z\d ]/i', $password, $matches) >= $this->policy->getMinimumDigits();
    }
}
