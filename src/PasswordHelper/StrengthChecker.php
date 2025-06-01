<?php

declare(strict_types=1);

namespace PasswordHelper;

/**
 * Evaluates and rates the strength of passwords.
 * 
 * This class provides a method to assess password strength based on various
 * factors including length, character variety, and complexity. The strength
 * is rated on a scale from 'Very Weak' to 'Very Strong'.
 * 
 * @package PasswordHelper
 */
class StrengthChecker
{
    /**
     * Current password strength score.
     *
     * @var int
     */
    protected $strength;

    /**
     * Available password strength levels, ordered from weakest to strongest.
     *
     * @var array<int, string>
     */
    protected $levels = [
        'Very Weak', 'Weak', 'Good', 'Very Good', 'Strong', 'Very Strong'
    ];

    /**
     * Creates a new password strength checker.
     */
    public function __construct()
    {
        $this->strength = 0;
    }

    /**
     * Evaluates and returns the strength rating of a given password.
     * 
     * The strength is calculated based on several factors:
     * - Password length (up to the minimum required length)
     * - Presence of multiple letter types (2 or more)
     * - Presence of numbers
     * - Presence of uppercase letters
     * - Presence of lowercase letters
     * - Presence of special characters
     * 
     * The final score is normalized to a 0-6 scale and mapped to a strength level.
     *
     * @param string $password The password to evaluate
     * @return string One of: 'Very Weak', 'Weak', 'Good', 'Very Good', 'Strong', 'Very Strong'
     */
    public function checkStrength(string $password): string
    {
        $score = 0;
        $score += min([strlen($password), Policy::MINIMUM_LENGTH]);
        $score += (int) (preg_match_all('/[a-z]/i', $password, $matches) >= 2);
        $score += (int) (bool) preg_match_all('/\d/', $password, $matches);
        $score += (int) (bool) preg_match_all('/[A-Z]/', $password, $matches);
        $score += (int) (bool) preg_match_all('/[a-z]/', $password, $matches);
        $score += (int) (bool) preg_match_all('/[^a-z\d ]/i', $password, $matches);
        $score = min([(int) ($score / 3), 6]);

        return $this->levels[$score];
    }
}
