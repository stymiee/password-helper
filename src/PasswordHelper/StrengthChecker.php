<?php

declare(strict_types=1);

namespace PasswordHelper;

/**
 * Evaluates and rates the strength of passwords using a comprehensive scoring system.
 * 
 * This class provides methods to assess password strength based on various factors
 * including length, character variety, complexity, and entropy. The strength is
 * scored on a scale from 1 to 100, with higher scores indicating stronger passwords.
 * 
 * @package PasswordHelper
 */
class StrengthChecker
{
    /**
     * Minimum password length for a strong password.
     *
     * @var int
     */
    private const MIN_LENGTH = 8;

    /**
     * Maximum length to consider for scoring.
     *
     * @var int
     */
    private const MAX_LENGTH = 20;

    /**
     * Common password patterns to check against.
     *
     * @var array<int, string>
     */
    private const COMMON_PATTERNS = [
        '123456', 'password', 'qwerty', 'admin', 'welcome',
        'monkey', 'letmein', 'dragon', 'baseball', 'iloveyou',
        'trustno1', 'sunshine', 'master', 'hello', 'shadow',
        'ashley', 'football', 'jesus', 'michael', 'ninja',
        'mustang', 'password1', '12345678', 'qwerty123', 'admin123'
    ];

    /**
     * Common dictionary words to check against.
     *
     * @var array<int, string>
     */
    private const COMMON_WORDS = [
        'password', 'admin', 'user', 'login', 'welcome',
        'hello', 'world', 'test', 'guest', 'default'
    ];

    /**
     * Creates a new password strength checker.
     */
    public function __construct()
    {
    }

    /**
     * Evaluates and returns the strength score of a given password.
     * 
     * The strength is calculated based on several factors:
     * - Length (up to 30 points)
     * - Character variety (up to 30 points)
     * - Complexity (up to 20 points)
     * - Entropy (up to 20 points)
     * 
     * The final score is normalized to a 1-100 scale.
     *
     * @param string $password The password to evaluate
     * @return int A score from 1 to 100 indicating password strength
     */
    public function checkStrength(string $password): int
    {
        if (empty($password)) {
            return 1;
        }

        if (strlen($password) < self::MIN_LENGTH) {
            return 1;
        }

        $score = 0;
        
        $score += $this->calculateLengthScore($password);
        $score += $this->calculateVarietyScore($password);
        $score += $this->calculateComplexityScore($password);
        $score += $this->calculateEntropyScore($password);
        
        return min(100, max(1, $score));
    }

    /**
     * Calculates the length-based score component.
     *
     * @param string $password The password to evaluate
     * @return int Score from 0 to 30
     */
    private function calculateLengthScore(string $password): int
    {
        $length = strlen($password);
        
        if ($length < self::MIN_LENGTH) {
            return 0;
        }
        
        if ($length >= self::MAX_LENGTH) {
            return 30;
        }
        
        return (int) (($length - self::MIN_LENGTH) / (self::MAX_LENGTH - self::MIN_LENGTH) * 30);
    }

    /**
     * Calculates the character variety score component.
     *
     * @param string $password The password to evaluate
     * @return int Score from 0 to 30
     */
    private function calculateVarietyScore(string $password): int
    {
        $score = 0;
        
        $score += $this->getCharacterTypeScore($password);
        $score += $this->getMixedCaseScore($password);
        $score += $this->getMixedTypesScore($password);
        
        return $score;
    }

    /**
     * Gets the score for different character types.
     *
     * @param string $password The password to evaluate
     * @return int Score from 0 to 20
     */
    private function getCharacterTypeScore(string $password): int
    {
        $score = 0;
        
        if (preg_match('/[A-Z]/', $password)) $score += 5;
        if (preg_match('/[a-z]/', $password)) $score += 5;
        if (preg_match('/\d/', $password)) $score += 5;
        if (preg_match('/[^a-zA-Z\d]/', $password)) $score += 5;
        
        return $score;
    }

    /**
     * Gets the score for mixed case usage.
     *
     * @param string $password The password to evaluate
     * @return int Score from 0 to 5
     */
    private function getMixedCaseScore(string $password): int
    {
        return (preg_match('/[A-Z]/', $password) && preg_match('/[a-z]/', $password)) ? 5 : 0;
    }

    /**
     * Gets the score for mixed character types.
     *
     * @param string $password The password to evaluate
     * @return int Score from 0 to 5
     */
    private function getMixedTypesScore(string $password): int
    {
        $types = 0;
        $types += (int) preg_match('/[A-Z]/', $password);
        $types += (int) preg_match('/[a-z]/', $password);
        $types += (int) preg_match('/\d/', $password);
        $types += (int) preg_match('/[^a-zA-Z\d]/', $password);
        
        return ($types >= 3) ? 5 : 0;
    }

    /**
     * Calculates the complexity score component.
     *
     * @param string $password The password to evaluate
     * @return int Score from 0 to 20
     */
    private function calculateComplexityScore(string $password): int
    {
        $score = 0;
        
        $score += $this->getRepeatedCharsScore($password);
        $score += $this->getSequentialCharsScore($password);
        $score += $this->getCommonPatternScore($password);
        $score += $this->getDictionaryWordScore($password);
        
        return $score;
    }

    /**
     * Gets the score for repeated characters.
     *
     * @param string $password The password to evaluate
     * @return int Score from 0 to 5
     */
    private function getRepeatedCharsScore(string $password): int
    {
        return !preg_match('/(.)\1{2,}/', $password) ? 5 : 0;
    }

    /**
     * Gets the score for sequential characters.
     *
     * @param string $password The password to evaluate
     * @return int Score from 0 to 5
     */
    private function getSequentialCharsScore(string $password): int
    {
        return !$this->hasSequentialChars($password) ? 5 : 0;
    }

    /**
     * Gets the score for common patterns.
     *
     * @param string $password The password to evaluate
     * @return int Score from 0 to 5
     */
    private function getCommonPatternScore(string $password): int
    {
        return !$this->hasCommonPattern($password) ? 5 : 0;
    }

    /**
     * Gets the score for dictionary words.
     *
     * @param string $password The password to evaluate
     * @return int Score from 0 to 5
     */
    private function getDictionaryWordScore(string $password): int
    {
        return !$this->hasDictionaryWord($password) ? 5 : 0;
    }

    /**
     * Calculates the entropy-based score component.
     *
     * @param string $password The password to evaluate
     * @return int Score from 0 to 20
     */
    private function calculateEntropyScore(string $password): int
    {
        $entropy = $this->calculateEntropy($password);
        return (int) min(20, ($entropy / 100) * 20);
    }

    /**
     * Calculates the entropy of a password.
     *
     * @param string $password The password to evaluate
     * @return float The entropy in bits
     */
    private function calculateEntropy(string $password): float
    {
        $length = strlen($password);
        $charset = $this->calculateCharsetSize($password);
        return $length * log($charset, 2);
    }

    /**
     * Calculates the size of the character set used in the password.
     *
     * @param string $password The password to evaluate
     * @return int The size of the character set
     */
    private function calculateCharsetSize(string $password): int
    {
        $charset = 0;
        
        if (preg_match('/[a-z]/', $password)) $charset += 26;
        if (preg_match('/[A-Z]/', $password)) $charset += 26;
        if (preg_match('/\d/', $password)) $charset += 10;
        if (preg_match('/[^a-zA-Z\d]/', $password)) $charset += 32;
        
        return $charset;
    }

    /**
     * Checks if the password contains sequential characters.
     *
     * @param string $password The password to check
     * @return bool True if sequential characters are found
     */
    private function hasSequentialChars(string $password): bool
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
    private function hasCommonPattern(string $password): bool
    {
        $password = strtolower($password);
        
        foreach (self::COMMON_PATTERNS as $pattern) {
            if (strpos($password, $pattern) !== false) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Checks if the password contains dictionary words.
     *
     * @param string $password The password to check
     * @return bool True if dictionary words are found
     */
    private function hasDictionaryWord(string $password): bool
    {
        foreach (self::COMMON_WORDS as $word) {
            if (stripos($password, $word) !== false) {
                return true;
            }
        }
        
        return false;
    }
}
