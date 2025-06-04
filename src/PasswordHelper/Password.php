<?php

declare(strict_types=1);

namespace PasswordHelper;

/**
 * Facade class that provides backward compatibility with the old API.
 *
 * This class wraps the new implementation while maintaining the same
 * public interface as the original Password Helper.
 *
 * @package PasswordHelper
 */
class Password
{
    private Policy $policy;
    private Generator $generator;
    private Validator $validator;
    private StrengthChecker $strengthChecker;

    /**
     * Creates a new password helper with the specified configuration.
     *
     * @param array<string, int> $config Optional configuration to override defaults
     */
    public function __construct(array $config = [])
    {
        $this->policy = new Policy(
            $config['minimumLength'] ?? 10,
            20, // maximumLength
            $this->calculateMinimumCharacterTypes($config),
            false, // allowRepeatedCharacters
            false, // allowSequentialCharacters
            false  // allowCommonPatterns
        );

        $this->generator = new Generator(
            $this->policy->getMinimumLength(),
            $this->policy->getMaximumLength()
        );

        $this->validator = new Validator($this->policy);
        $this->strengthChecker = new StrengthChecker();
    }

    /**
     * Generates a new password that meets the policy requirements.
     *
     * @return string The generated password
     */
    public function generate(): string
    {
        return $this->generator->generate();
    }

    /**
     * Validates that a password meets the complexity requirements.
     *
     * @param string $password The password to validate
     * @return bool True if the password meets all requirements
     */
    public function validateComplexity(string $password): bool
    {
        return $this->validator->isValidPassword($password);
    }

    /**
     * Checks the strength of a password and returns a descriptive rating.
     *
     * @param string $password The password to check
     * @return string The strength rating: "Very Weak", "Weak", "Fair", "Good", "Strong", or "Very Strong"
     */
    public function checkStrength(string $password): string
    {
        $score = $this->strengthChecker->checkStrength($password);

        return match(true) {
            $score < 20 => 'Very Weak',
            $score < 40 => 'Weak',
            $score < 60 => 'Fair',
            $score < 80 => 'Good',
            $score < 90 => 'Strong',
            default => 'Very Strong'
        };
    }

    /**
     * Hashes a password using PHP's password_hash function.
     *
     * @param string $password The password to hash
     * @return string The hashed password
     */
    public function hash(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Verifies a password against a hash.
     *
     * @param string $password The password to verify
     * @param string $hash The hash to verify against
     * @return bool True if the password matches the hash
     */
    public function verify(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    /**
     * Checks if a hash needs to be rehashed.
     *
     * @param string $hash The hash to check
     * @return bool True if the hash should be rehashed
     */
    public function checkForRehash(string $hash): bool
    {
        return password_needs_rehash($hash, PASSWORD_DEFAULT);
    }

    /**
     * Calculates the minimum character types based on the old configuration.
     *
     * @param array<string, int> $config The configuration array
     * @return int The minimum number of character types required
     */
    private function calculateMinimumCharacterTypes(array $config): int
    {
        $types = 0;

        if (($config['minimumDigits'] ?? 1) > 0) $types++;
        if (($config['minimumSpecialChars'] ?? 1) > 0) $types++;
        if (($config['minimumUppercase'] ?? 1) > 0 || ($config['minimumLowercase'] ?? 1) > 0) $types++;

        return $types;
    }
}
