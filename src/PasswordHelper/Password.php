<?php

declare(strict_types=1);

namespace PasswordHelper;

/**
 * Main class for password management operations.
 * 
 * This class provides a high-level interface for password operations including:
 * - Password generation
 * - Password validation
 * - Password strength checking
 * - Password hashing and verification
 * 
 * @package PasswordHelper
 * @codeCoverageIgnore
 */
class Password
{
    /**
     * Password strength checker instance.
     *
     * @var StrengthChecker
     */
    protected $checker;

    /**
     * Password generator instance.
     *
     * @var Generator
     */
    protected $generator;

    /**
     * Password policy configuration.
     *
     * @var Policy
     */
    private $policy;

    /**
     * Password validator instance.
     *
     * @var Validator
     */
    protected $validator;

    /**
     * Creates a new Password instance with optional configuration.
     *
     * @param array $config Configuration options for password policy:
     *                      - minimumDigits: Minimum number of digits required
     *                      - minimumLowercase: Minimum number of lowercase letters required
     *                      - minimumSpecialChars: Minimum number of special characters required
     *                      - minimumUppercase: Minimum number of uppercase letters required
     *                      - minimumLetters: Minimum number of total letters required
     *                      - minimumLength: Minimum password length required
     */
    public function __construct(array $config = [])
    {
        $this->policy = new Policy($config);
        $this->checker = new StrengthChecker();
        $this->validator = new Validator($this->policy);
        $this->generator = new Generator($this->policy, $this->validator);
    }

    /**
     * Returns debug information about the current password policy configuration.
     *
     * @return array<string, int> Array containing all minimum requirements
     */
    public function __debugInfo(): array
    {
        return [
            'minimumDigits' => $this->policy->getMinimumDigits(),
            'minimumLowercase' => $this->policy->getMinimumLowercase(),
            'minimumSpecialChars' => $this->policy->getMinimumSpecialChars(),
            'minimumUppercase' => $this->policy->getMinimumUppercase(),
            'minimumLetters' => $this->policy->getMinimumLetters(),
            'minimumLength' => $this->policy->getMinimumLength(),
        ];
    }

    /**
     * Generates a new password that satisfies the current password policy.
     *
     * @return string A randomly generated password meeting all policy requirements
     * @throws \Exception If random number generation fails
     */
    public function generate(): string
    {
        return $this->generator->generatePassword();
    }

    /**
     * Validates that a password satisfies all requirements of the current password policy.
     *
     * @param string $password The password to validate
     * @return bool True if the password meets all policy requirements, false otherwise
     */
    public function validateComplexity(string $password): bool
    {
        return $this->validator->isValidPassword($password);
    }

    /**
     * Evaluates and returns the strength rating of a given password.
     *
     * @param string $password The password to evaluate
     * @return string One of: 'Very Weak', 'Weak', 'Good', 'Very Good', 'Strong', 'Very Strong'
     */
    public function checkStrength(string $password): string
    {
        return $this->checker->checkStrength($password);
    }

    /**
     * Creates a secure hash of the given password using PHP's password_hash().
     *
     * @see https://secure.php.net/manual/en/function.password-hash.php
     *
     * @param string $password The plain text password to hash
     * @return string The hashed password
     */
    public function hash(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Verifies that a password matches a hash.
     *
     * @see https://secure.php.net/manual/en/function.password-verify.php
     *
     * @param string $password The plain text password to verify
     * @param string $hash The hash to verify against
     * @return bool True if the password matches the hash, false otherwise
     */
    public function verify(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    /**
     * Returns information about the given hash.
     *
     * @see https://secure.php.net/manual/en/function.password-get-info.php
     *
     * @param string $hash A hash created by password_hash()
     * @return array{
     *     algo: int,
     *     algoName: string,
     *     options: array<string, mixed>
     * } Information about the hash
     */
    public function getInfo(string $hash): array
    {
        return password_get_info($hash);
    }

    /**
     * Checks if the given hash should be rehashed to match the current algorithm and options.
     *
     * @see https://secure.php.net/manual/en/function.password-needs-rehash.php
     *
     * @param string $hash A hash created by password_hash()
     * @return bool True if the hash should be rehashed, false otherwise
     */
    public function checkForRehash(string $hash): bool
    {
        return password_needs_rehash($hash, PASSWORD_DEFAULT);
    }
}